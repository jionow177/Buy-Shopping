<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserDetail;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MidtransController extends Controller
{

    public function planPayWithMidtrans(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $midtrans_secret = $payment_setting['midtrans_secret'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
        $midtrans_mode = $payment_setting['midtrans_mode'];

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            $get_amount = round($plan->price);

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $get_amount = $plan->price - $discount_value;
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = Auth::user()->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = $midtrans_secret;
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            if ($midtrans_mode == 'sandbox') {
                \Midtrans\Config::$isProduction = false;
            } else {
                \Midtrans\Config::$isProduction = true;
            }
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            // // Set your Merchant Server Key
            // \Midtrans\Config::$serverKey = $midtrans_secret;
            // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            // \Midtrans\Config::$isProduction = false;
            // // Set sanitization on (default)
            // \Midtrans\Config::$isSanitized = true;
            // // Set 3DS transaction for credit card to true
            // \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderID,
                    'gross_amount' => $get_amount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => '8787878787',
                ),
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $authuser = Auth::user();
            $authuser->plan = $plan->id;
            $authuser->save();

            PlanOrder::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $get_amount == null ? 0 : $get_amount,
                    'price_currency' => $currency,
                    'txn_id' => '',
                    'payment_type' => __('Midtrans'),
                    'payment_status' => 'pending',
                    'receipt' => null,
                    'user_id' => $authuser->id,
                ]
            );
            $data = [
                'snap_token' => $snapToken,
                'midtrans_secret' => $midtrans_secret,
                'order_id' => $orderID,
                'plan_id' => $plan->id,
                'amount' => $get_amount,
                'fallback_url' => 'plan.get.midtrans.status'
            ];

            return view('midtras.payment', compact('data'));
        }
    }

    public function planGetMidtransStatus(Request $request)
    {
        $response = json_decode($request->json, true);
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $plan = Plan::find($request['plan_id']);
            $user = auth()->user();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $Order                 = PlanOrder::where('order_id', $request['order_id'])->first();
                $Order->payment_status = 'succeeded';
                $Order->save();

                $assignPlan = $user->assignPlan($plan->id);

                if (!empty($request->coupon_id)) {
                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }
                Utility::referralTransaction($plan);

                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', $response['status_message']);
        }

    }

    public function storePayWithMidtrans(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        $validator = \Validator::make(

            $request->all(),
            [
                'name' => 'required|max:120',
                'phone' => 'required',
                'billing_address' => 'required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('store.slug', $slug)->with('error', __('All field is required.'));
        }

        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off'){
            try {
                $shipping = Shipping::where('store_id', $store->id)->first();
                if (!empty($shipping) && $store->enable_shipping == 'on') {
                    if ($request->shipping_price == '0.00') {
                        return redirect()->route('store.slug', $slug)->with('error', __('Please select shipping.'));
                    }
                }
                $cart = session()->get($slug);

                $userdetail = new UserDetail();

                $store = Store::where('slug', $slug)->first();

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

                if (!empty($cart) && isset($cart['products'])) {
                    $products = $cart;
                } else {
                    return redirect()->back()->with('error', __('Please add to product into cart'));
                }
                if (!empty($cart['customer'])) {
                    $customers = $cart['customer'];
                } else {
                    $customers = [];
                }

                $store = Store::where('slug', $slug)->first();
                $companyPaymentSetting = Utility::getPaymentSetting($store->id);
                $total_tax = $sub_total = $total = $sub_tax = 0;
                $total        = 0;
                $sub_tax      = 0;
                $sub_total    = 0;
                $product_name = [];
                $product_id   = [];
                $totalprice   = 0;
                $tax_name     = [];

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

                $coupon_id = null;
                $price     = $total + $total_tax;
                $price = $totalprice + $tax_price;
                if (isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                        $price          = $price - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $price          = $price - $discount_value;
                    }
                }
                if ($products) {
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
                    $cart['totalprice'] = $totalprice;
                    $cart['coupon_id'] = $request->coupon_id;
                    $cart['coupon_json'] = json_encode($coupon);
                    $cart['dicount_price'] = $request->dicount_price;
                    $cart['currency_code'] = $store->currency_code;
                    $cart['user_id'] = $store['id'];

                    session()->put($slug, $cart);

                    $midtrans_secret       = $companyPaymentSetting['midtrans_secret'];
                    $currency = isset($store['currency_code']) ? $store['currency_code'] : 'USD';
                    $midtrans_mode = $companyPaymentSetting['midtrans_mode'];
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    if (isset($midtrans_secret)) {

                        // Set your Merchant Server Key
                        \Midtrans\Config::$serverKey = $midtrans_secret;
                        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                        if ($midtrans_mode == 'sandbox') {
                            \Midtrans\Config::$isProduction = false;
                        } else {
                            \Midtrans\Config::$isProduction = true;
                        }
                        // Set sanitization on (default)
                        \Midtrans\Config::$isSanitized = true;
                        // Set 3DS transaction for credit card to true
                        \Midtrans\Config::$is3ds = true;

                        // // Set your Merchant Server Key
                        // \Midtrans\Config::$serverKey = $midtrans_secret;
                        // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                        // \Midtrans\Config::$isProduction = false;
                        // // Set sanitization on (default)
                        // \Midtrans\Config::$isSanitized = true;
                        // // Set 3DS transaction for credit card to true
                        // \Midtrans\Config::$is3ds = true;

                        $params = array(
                            'transaction_details' => array(
                                'order_id' => $orderID,
                                'gross_amount' => $totalprice,
                            ),
                            'customer_details' => array(
                                'first_name' => $cust_details['name'],
                                'last_name' => '',
                                'email' => $cust_details['email'],
                                'phone' => '8787878787',
                            ),
                        );
                        $snapToken = \Midtrans\Snap::getSnapToken($params);


                        $data = [
                            'snap_token' => $snapToken,
                            'midtrans_secret' => $midtrans_secret,
                            'slug'=>$slug,
                            'product_id'=>$product_id,
                            'amount'=>$totalprice,
                            'fallback_url' => 'store.midtrans.status'
                        ];

                        return view('midtras.payment', compact('data'));
                    } else {
                        return redirect()->back()->with('error', 'Please enter valid midtrans secret key');
                    }
                } else {
                    return redirect()->back()->with('error', 'Product not found.');
                }
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error','You Need To Login');
        }

    }
    public function getStorePaymentStatus(Request $request)
    {
        $slug       = $request->slug;
        $getAmount  = $request->amount;
        $product_id = $request->product_id;

        try {
            $store          = Store::where('slug', $slug)->first();
            $cart           = session()->get($slug);
            $products       = $cart['all_products'];
            $cust_details   = $cart['cust_details'];
            $shipping_data  = $cart['shipping_data'];
            if (isset($cart['coupon']['data_id'])) {
                $coupon     = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            } else {
                $coupon     = '';
            }

            $customer               = Auth::guard('customers')->user();
            $order                  = new Order();
            $order->order_id        = '#' . time();
            $order->name            = isset($cust_details['name']) ? $cust_details['name'] : '';
            $order->email           = isset($cust_details['email']) ? $cust_details['email'] : '';
            $order->card_number     = '';
            $order->card_exp_month  = '';
            $order->card_exp_year   = '';
            $order->status          = 'pending';
            $order->user_address_id = isset($cust_details['id']) ? $cust_details['id'] : '';
            $order->shipping_data   = $shipping_data;
            $order->product_id      = implode(',', $product_id);
            $order->price           = $getAmount;
            $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
            $order->coupon_json     = json_encode($coupon);
            $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $order->product         = json_encode($products);
            $order->price_currency  = $store->currency_code;
            $order->txn_id          = isset($pay_id) ? $pay_id : '';
            $order->payment_type    = 'Midtrans';
            $order->payment_status  = 'approved';
            $order->receipt         = '';
            $order->user_id         = $store['id'];
            $order->customer_id     = isset($customer->id) ? $customer->id : 0;
            $order->save();

            //webhook
            $module     = 'New Order';
            $webhook    =  Utility::webhook($module, $store->id);
            if ($webhook) {
                $parameter = json_encode($order);
                //
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status != true) {
                    $msg  = 'Webhook call failed.';
                }
            }


            if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {

                foreach ($products['products'] as $k_pro => $product_id) {

                    $purchased_product              = new PurchasedProducts();
                    $purchased_product->product_id  = $product_id['product_id'];
                    $purchased_product->customer_id = isset($customer->id) ? $customer->id : 0;
                    $purchased_product->order_id    = $order->id;
                    $purchased_product->save();
                }
            }
            $order_email    = $order->email;
            $owner          = User::find($store->created_by);
            $owner_email    = $owner->email;
            $order_id       = Crypt::encrypt($order->id);
            // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                $dArr = [
                    'order_name' => $order->name,
                ];
                $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
            // }
            if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                Utility::order_create_owner($order, $owner, $store);
                Utility::order_create_customer($order, $customer, $store);
            }
            $msg = redirect()->route(
                'store-complete.complete',
                [
                    $store->slug,
                    Crypt::encrypt($order->id),
                ]
            )->with('success', __('Transaction has been success'));

            session()->forget($slug);

            return $msg;
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', __($th->getMessage()));
        }

    }
}
