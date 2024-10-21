<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\UserDetail;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PayfastController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();
            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);
            if ($plan) {
                $plan_amount = $plan->price;
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = Auth::user();
                if ($request->coupon_amount > 0 && $request->coupon_code != null) {
                    $coupons = Coupon::where('code', $request->coupon_code)->first();
                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $order_id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                        $discount_value = ($plan_amount / 100) * $coupons->discount;
                        $plan_amount         = $plan_amount - $discount_value;
                    }
                }

                $success = Crypt::encrypt([
                    'plan' => $plan->toArray(),
                    'order_id' => $order_id,
                    'plan_amount' => $plan_amount
                ]);

                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '',
                    'merchant_key' => !empty($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '',
                    'return_url' => route('payfast.payment.success', $success),
                    'cancel_url' => route('plans.index'),
                    'notify_url' => route('plans.index'),
                    // Buyer details
                    'name_first' => $user->name,
                    'name_last' => '',
                    'email_address' => $user->email,
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => number_format(sprintf('%.2f', $plan_amount), 2, '.', ''),
                    'item_name' => $plan->name,
                );

                $passphrase = !empty($payment_setting['payfast_signature']) ? $payment_setting['payfast_signature'] : '';
                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }

                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm,
                ]);
            }
        }
    }
    public function generateSignature($data, $passPhrase = null)
    {

        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }

    public function success($success)
    {
        try {
            $user = Auth::user();
            $data = Crypt::decrypt($success);
            $payment_setting = Utility::getAdminPaymentSetting();

            $order = new PlanOrder();
            $order->order_id = $data['order_id'];
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $data['plan']['name'];
            $order->plan_id = $data['plan']['id'];
            $order->price = $data['plan_amount'];
            $order->price_currency = !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
            $order->txn_id = $data['order_id'];
            $order->payment_type = __('PayFast');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $assignPlan = $user->assignPlan($data['plan']['id']);
            Utility::referralTransaction($data['plan']);

            if ($assignPlan['is_success']) {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        } catch (Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e));
        }
    }

    //Coingate Pago Prepare Payment
    public function Paywithpayfast($slug, Request $request)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    // return redirect()->route('store.slug', $slug)->with('error', __('Please select shipping.'));
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => 'Please select shipping.'
                        ]
                    );
                }
            }
            if (empty($store)) {
                return redirect()->route('store.slug', $slug)->with('error', __('Store not available.'));
            }

            $validator = \Validator::make(

                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => 'All field is required.'
                    ]
                );
            }

            $userdetail = new UserDetail();
            $userdetail['store_id'] = $store->id;
            $userdetail['name']     = $request->name;
            $userdetail['email']    = $request->email;
            $userdetail['phone']    = $request->phone;

            $userdetail['custom_field_title_1'] = $request->custom_field_title_1;
            $userdetail['custom_field_title_2'] = $request->custom_field_title_2;
            $userdetail['custom_field_title_3'] = $request->custom_field_title_3;
            $userdetail['custom_field_title_4'] = $request->custom_field_title_4;


            $userdetail['billing_address']  = $request->billing_address;
            $userdetail['shipping_address'] = !empty($request->shipping_address) ? $request->shipping_address : '-';
            $userdetail['special_instruct'] = $request->special_instruct;
            $userdetail->save();
            $userdetail->id;

            $cart     = session()->get($slug);
            $products = $cart;
            $order_id = $request['order_id'];
            if (empty($cart)) {
                return redirect()->route('store.slug', $slug)->with('error', __('Please add to product into cart.'));
            }
            $cust_details = [
                "id" => $userdetail->id,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "custom_field_title_1" => $request->custom_field_title_1,
                "custom_field_title_2" => $request->custom_field_title_2,
                "custom_field_title_3" => $request->custom_field_title_3,
                "custom_field_title_4" => $request->custom_field_title_4,
                "billing_address" => $request->billing_address,
                "shipping_address" => $request->shipping_address,
                "special_instruct" => $request->special_instruct,
            ];
            if (!empty($request->coupon_id)) {
                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }
            //dd($coupon);
            $store        = Store::where('slug', $slug)->first();
            $user_details = $cust_details;

            $store_payment_setting = Utility::getPaymentSetting($store->id);

            $total        = 0;
            $sub_tax      = 0;
            $sub_total    = 0;
            $total_tax    = 0;
            $product_name = [];
            $product_id   = [];
            $totalprice   = 0;
            $tax_name     = [];

            $get_amount    = 0;
            $sub_tax        = 0;
            $sub_totalprice = 0;
            $total_tax      = 0;
            $product_name   = [];
            $product_id     = [];

            foreach ($products['products'] as $key => $product) {
                if ($product['variant_id'] == 0) {
                    $new_qty                = $product['originalquantity'] - $product['quantity'];
                    $product_edit           = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice     += $product['price'] * $product['quantity'] + $tax_price;
                    $product_name[] = $product['product_name'];
                    $product_id[]   = $product['id'];
                } elseif ($product['variant_id'] != 0) {
                    $new_qty                   = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant           = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice     += $product['variant_price'] * $product['quantity'] + $tax_price;
                    $product_name[] = $product['product_name'] . ' - ' . $product['variant_name'];
                    $product_id[]   = $product['id'];
                }
            }
            if (!empty($request->coupon_id)) {
                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }

            if ($products) {
                $coupon_id = null;
                $price     = $total + $total_tax;
                $totalprice = $totalprice + $tax_price;
                if (isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                        $price          = $price - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $price          = $price - $discount_value;
                    }
                }
                if (!empty($request->shipping_id)) {
                    $shipping = Shipping::find($request->shipping_id);
                    if (!empty($shipping)) {
                        $totalprice     = $price + $shipping->price;
                        $shipping_name  = $shipping->name;
                        $shipping_price = $shipping->price;
                        $shipping_data  = json_encode(
                            [
                                'shipping_name' => $shipping_name,
                                'shipping_price' => $shipping_price,
                                'location_id' => $shipping->location_id,
                            ]
                        );
                    }
                } else {
                    $shipping_data = '';
                }

                $cart['cust_details'] = $cust_details;
                $cart['shipping_data'] = $shipping_data;
                $cart['product_id'] = $product_id;
                $cart['all_products'] = $products;
                if ($coupon != "") {
                    if ($coupon['enable_flat'] == 'off') {
                        $discount_value = ($totalprice / 100) * $coupon['discount'];
                        $totalprice          = $totalprice - $discount_value;
                    } else {
                        $discount_value = $coupon['flat_discount'];
                        $totalprice          = $totalprice - $discount_value;
                    }
                }
                $totalprice = str_replace(' ', '', str_replace(',', '', str_replace($store->currency, '', $request->total_price)));
                $cart['cust_details'] = $cust_details;
                $cart['shipping_data'] = $shipping_data;
                $cart['product_id'] = $product_id;
                $cart['all_products'] = $products;
                $cart['totalprice'] = $totalprice;
                $cart['coupon_id'] = $request->coupon_id;
                $cart['coupon_json'] = json_encode($coupon);
                $cart['dicount_price'] = $request->dicount_price;
                $cart['currency_code'] = $store->currency_code;
                $cart['user_id'] = $store['id'];
                session()->put($slug, $cart);

                // if ($products) {
                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
                }

                $customer =  Auth::guard('customers')->user();
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $success = Crypt::encrypt([
                    'order_id' => $order_id,
                    'get_amount' => $totalprice
                ]);
                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($store_payment_setting['payfast_merchant_id']) ? $store_payment_setting['payfast_merchant_id'] : '',
                    'merchant_key' => !empty($store_payment_setting['payfast_merchant_key']) ? $store_payment_setting['payfast_merchant_key'] : '',
                    'return_url' => route('payfast.success', [$success, $store->slug]),
                    'cancel_url' => route('payment.cancelled', $slug),
                    'notify_url' => route('plans.index'),
                    // Buyer details
                    'name_first' => $cust_details['name'],
                    'name_last' => '',
                    'email_address' => $cust_details['email'],
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => number_format(sprintf('%.2f', $totalprice), 2, '.', ''),
                    'item_name' => $product['product_name'],
                );
                $passphrase = !empty($store_payment_setting['payfast_signature']) ? $store_payment_setting['payfast_signature'] : '';
                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }
                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm,
                ]);
            } else {

                return redirect()->back()->with('error', __('You need to login'));
            }
        }
    }

    public function payfastsuccess($success, $slug)
    {
        try {
            $cart = session()->get($slug);

            $products     = $cart['products'];
            $store        = Store::where('slug', $slug)->first();



            $product_name = [];
            $product_id   = [];
            $tax_name     = [];
            $totalprice   = 0;

            foreach ($products as $key => $product) {
                if ($product['variant_id'] == 0) {
                    $new_qty                = $product['originalquantity'] - $product['quantity'];
                    $product_edit           = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice     += $product['price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[]   = $product['id'];
                } elseif ($product['variant_id'] != 0) {
                    $new_qty                   = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant           = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice     += $product['variant_price'] * $product['quantity'];
                    $product_name[] = $product['product_name'] . ' - ' . $product['variant_name'];
                    $product_id[]   = $product['id'];
                }
            }

            $cart     = session()->get($slug);
            // dd($cart);
            $cust_details = $cart['cust_details'];
            $shipping_data = $cart['shipping_data'];
            $product_id = $cart['product_id'];
            $totalprice = $cart['totalprice'];
            $coupon = $cart['coupon_json'];
            $products = $cart['all_products'];

            if (Utility::CustomerAuthCheck($store->slug)) {
                $customer = Auth::guard('customers')->user()->id;
            } else {
                $customer = 0;
            }

            $customer =  Auth::guard('customers')->user();
            if (isset($cart['coupon']['data_id'])) {
                $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            } else {
                $coupon = '';
            }

            $customer               = Auth::guard('customers')->user();
            $data = Crypt::decrypt($success);

            $discount_price_order = !empty($cart['dicount_price']) ? $cart['dicount_price'] : '0';
            $customer               = Auth::guard('customers')->user();
            $order                  = new Order();
            $order->order_id        = '#' . time();
            $order->name            = $cust_details['name'];
            $order->email           = $cust_details['email'];
            $order->card_number     = '';
            $order->card_exp_month  = '';
            $order->card_exp_year   = '';
            $order->status          = 'pending';
            $order->phone           = $cust_details['phone'];
            $order->user_address_id = $cust_details['id'];
            $order->shipping_data   = !empty($shipping_data) ? $shipping_data : '';
            $order->product_id      = implode(',', $product_id);
            $order->price           = $totalprice;
            $order->coupon          = $cart['coupon_id'];
            $order->coupon_json     = $coupon;
            $order->discount_price  = !empty($cart['dicount_price']) ? $cart['dicount_price'] : '0';
            $order->coupon          = $cart['coupon_id'];
            $order->product         = json_encode($products);
            $order->price_currency  = $cart['currency_code'];
            $order->txn_id          = '';
            $order->payment_type    = __('Payfast');
            $order->payment_status  = 'approved';
            $order->receipt         = '';
            $order->user_id         = $cart['user_id'];
            $order->customer_id     = isset($customer->id) ? $customer->id : '';
            $order->save();

            //webhook
            $module = 'New Order';
            $webhook =  Utility::webhook($module, $store->id);
            if ($webhook) {
                $parameter = json_encode($product);
                //
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                // dd($status);
                if ($status != true) {
                    $msg  = 'Webhook call failed.';
                }
            }

            if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {
                foreach ($products['products'] as $k_pro => $product_id) {

                    $purchased_product = new PurchasedProducts();
                    $purchased_product->product_id  = $product_id['product_id'];
                    $purchased_product->customer_id = $customer->id;
                    $purchased_product->order_id   = $order->id;
                    $purchased_product->save();
                }
            }
            session()->forget($slug);

            return redirect()->route(
                'store-complete.complete',
                [
                    $store->slug,
                    Crypt::encrypt($order->id),
                ]
            )->with('success', __('Transaction has been success') . ((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e));
        }
    }

    public function payment(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $payment_setting = Utility::getAdminPaymentSetting();

        $authuser   = \Auth::user();
        $authuser->plan = $plan->id;
        $authuser->save();

        $assignPlan = $authuser->assignPlan($plan->id);
        $coupons = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', '1')->first();
        $user = Auth::user();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

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

            if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                try {

                    $authuser->cancel_subscription($authuser->id);
                } catch (\Exception $exception) {
                    \Log::debug($exception->getMessage());
                }
            }

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $planorder                 = new PlanOrder();
            $planorder->order_id       = $orderID;
            $planorder->name           = $authuser->name;
            $planorder->email           = $authuser->email;
            $planorder->card_number    = '';
            $planorder->card_exp_month = '';
            $planorder->card_exp_year  = '';
            $planorder->plan_name      = $plan->name;
            $planorder->plan_id        = $plan->id;
            $planorder->price          = 0;
            $planorder->price_currency = !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
            $planorder->txn_id         = '';
            $planorder->payment_type   = __('Payfast');
            $planorder->payment_status = 'succeeded';
            $planorder->receipt        = '';
            $planorder->user_id        = $authuser->id;
            $planorder->store_id       = $authuser->current_store;
            $planorder->save();
            $assignPlan = $authuser->assignPlan($plan->id, $request->frequency);
            // $res['msg'] = __("Plan successfully upgraded.");
            // $res['flag'] = 2;

            $data['msg']  = __("Plan successfully upgraded.");
            $data['flag'] = 2;
        }
        return $data;
    }
}
