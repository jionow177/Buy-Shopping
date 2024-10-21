<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BanktransferController extends Controller
{
    public function planPayWithBanktransfer(Request $request)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'bank_transfer_invoice' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $image_size = $request->file('bank_transfer_invoice')->getSize();
        $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
        if ($result == 1) {
            $filenameWithExt  = $request->file('bank_transfer_invoice')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('bank_transfer_invoice')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;
            $settings = Utility::getStorageSetting();
            if ($settings['storage_setting'] == 'local') {
                $dir  = 'uploads/bank_transfer_invoice/';
            }
            $path = Utility::upload_file($request, 'bank_transfer_invoice', $fileNameToStores, $dir, []);
            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        } else {
            return redirect()->back()->with('error', 'Plan storage limit is over so please upgrade the plan.');
        }

        if (Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();
            $user   = Auth::user();
            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);
            $coupons_id = '';
            $order = PlanOrder::where('plan_id', $plan->id)->where('payment_status', 'Pending')->first();
            if ($order) {
                return redirect()->route('plans.index')->with('error', __('You already send Payment request to this plan.'));
            }
            if ($plan) {
                /* Check for code usage */
                $plan->discounted_price = false;
                $price                  = $plan->price;
                if (!empty($request->coupon)) {
                    // $request->coupon = trim($request->coupon);
                    $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                    if (!empty($coupons)) {
                        $usedCoupun             = $coupons->used_coupon();
                        $discount_value         = ($price / 100) * $coupons->discount;
                        $plan->discounted_price = $price - $discount_value;
                        if ($usedCoupun >= $coupons->limit) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $price      = $price - $discount_value;

                        $coupons_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                // $coupons = Coupon::find($coupons->id);
                $user = Auth::user();
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                if ($price <=  0.0) {
                    $authuser       = Auth::user();

                    $authuser->plan = $plan->id;
                    $authuser->save();

                    $assignPlan = $authuser->assignPlan($plan->id, $request->paypal_payment_frequency);

                    if (!empty($coupons)) {
                        $userCoupon            = new UserCoupon();
                        $userCoupon->user   = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();


                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }


                    if ($assignPlan['is_success'] == true && !empty($plan)) {
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                        $planorder                 = new PlanOrder();
                        $planorder->order_id = $orderID;
                        $planorder->name = $authuser->name;
                        $planorder->email = $authuser->email;
                        $planorder->card_number = null;
                        $planorder->card_exp_month = null;
                        $planorder->card_exp_year = null;
                        $planorder->plan_name = $plan->name;
                        $planorder->plan_id = $plan->id;
                        $planorder->price = $price == null ? 0 : $price;
                        $planorder->price_currency = $payment_setting['currency'];
                        $planorder->txn_id = '';
                        $planorder->payment_type = __('Bank Transfer');
                        $planorder->payment_status = 'pending';
                        $planorder->receipt = $file;
                        $planorder->user_id = $authuser->id;
                        $planorder->store_id = $authuser->current_store;

                        $planorder->save();

                        return redirect()->route('plans.index')->with('success', __("Plan Successfully Activated"));
                    }
                } else {
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    $planorder                 = new PlanOrder();
                    $planorder->order_id = $orderID;
                    $planorder->name = $user->name;
                    $planorder->email = $user->email;
                    $planorder->card_number = null;
                    $planorder->card_exp_month = null;
                    $planorder->card_exp_year = null;
                    $planorder->plan_name = $plan->name;
                    $planorder->plan_id = $plan->id;
                    $planorder->price = $price == null ? 0 : $price;
                    $planorder->price_currency = $payment_setting['currency'];
                    $planorder->txn_id = '';
                    $planorder->payment_type = __('Bank Transfer');
                    $planorder->payment_status = 'pending';
                    $planorder->receipt = $file;
                    $planorder->user_id = $user->id;
                    $planorder->store_id = $user->current_store;

                    $planorder->save();

                    if ($request->coupon != '') {
                        $coupons = Coupon::where('code', $request->coupon)->first();
                        if (!empty($coupons)) {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    return redirect()->route('plans.index')->with('success', __('Plan payment request send successfully'));
                }
            } else {
                return redirect()->back()->with('error', 'Plan is deleted.');
            }
        }
    }
    public function bank_transfer_show($order_id)
    {
        // dd($order_id);
        $admin_payments_setting = Utility::getAdminPaymentSetting();
        $order = PlanOrder::find($order_id);

        return view('plans.view', compact('order', 'admin_payments_setting'));
    }
    public function StatusEdit(Request $request, $order_id)
    {
        $order = PlanOrder::find($order_id);
        $order->payment_status = $request->status;
        $order->update();
        if ($request->status == 'Approved') {
            $user =  User::where('id', $order->user_id)->first();
            $assignPlan = $user->assignPlan($order->plan_id);
            $plan = Plan::find($order->plan_id);
            Utility::referralTransaction($plan,$user);
        }
        return redirect()->back()->with('success', __('Plan payment successfully updated.'));
    }
}
