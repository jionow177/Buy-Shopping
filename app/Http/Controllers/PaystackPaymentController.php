<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\RedirectResponse;

class PaystackPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;

    public function planPayWithPaystack(Request $request)
    {
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $coupon_id = '';
        if ($plan) {

            /* Check for code usage */
            $plan->discounted_price = false;
            $price = $plan->price;
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if ($usedCoupun >= $coupons->limit) {
                        return Utility::error_res(__('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                    $coupon_id = $coupons->id;
                } else {
                    return Utility::error_res(__('This coupon code is invalid or has expired.'));
                }
            }

                $res_data['email'] = Auth::user()->email;
                $res_data['total_price'] = $price;
                $res_data['currency'] = !empty($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : 'USD';
                $res_data['flag'] = 1;
                $res_data['coupon'] = $coupon_id;
                return $res_data;
            // }
        } else {
            return Utility::error_res(__('Plan is deleted.'));
        }
    }

    public function getPaymentStatus($code, $plan_id, Request $request)
    {
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan               = Plan::find($plan_id);

        if ($plan) {

            // try
            // {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            $result = array();
            //The parameter after verify/ is the transaction reference to be verified
            $url = "https://api.paystack.co/transaction/verify/$code";
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                [
                    'Authorization: Bearer ' . $admin_payment_setting['paystack_secret_key'],
                ]
            );
            $responce = curl_exec($ch);
            curl_close($ch);
            if ($responce) {
                $result = json_decode($responce, true);
            }
            if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
                $status = $result['data']['status'];
                if ($request->has('coupon_id') && $request->coupon_id != '') {
                    $coupons = Coupon::find($request->coupon_id);
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

                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $result['data']['amount'] / 100;
                $planorder->price_currency = 'NGN';
                $planorder->txn_id         = $code;
                $planorder->payment_type   = __('Paystack');
                $planorder->payment_status = $result['data']['status'];
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

                $assignPlan = $user->assignPlan($plan->id);
                Utility::referralTransaction($plan);

                if ($assignPlan['is_success']) {


                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {


                    return redirect()->route('plans.index')->with('error', $assignPlan['error']);
                }
            } else {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }

            // }
            // catch(\Exception $e)
            // {
            //     return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
            // }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planpaymentSetting()
    {
        $payment_setting = Utility::getAdminPaymentSetting();

        $this->currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $this->secret_key = isset($payment_setting['paystack_secret_key']) ? $payment_setting['paystack_secret_key'] : '';
        $this->public_key = isset($payment_setting['paystack_public_key']) ? $payment_setting['paystack_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paystack_enabled']) ? $payment_setting['is_paystack_enabled'] : 'off';
    }
}
