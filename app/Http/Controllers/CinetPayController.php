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

class CinetPayController extends Controller
{
    public function planPayWithCinetPay(Request $request)
    {
        $payment_setting    = Utility::getAdminPaymentSetting();
        $cinetpay_api_key   = !empty($payment_setting['cinetpay_api_key']) ? $payment_setting['cinetpay_api_key'] : '';
        $cinetpay_site_id   = !empty($payment_setting['cinetpay_site_id']) ? $payment_setting['cinetpay_site_id'] : '';
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';
        $planID             = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan               = Plan::find($planID);
        $orderID            = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser           = Auth::user();

        if ($plan) {
            /* Check for code usage */
            $get_amount = $plan->price;

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;

                    $get_amount = $plan->price - $discount_value;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }

                    if ($get_amount <= 0) {
                        $authuser = User::find(Auth::user()->id);
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {

                            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon = new UserCoupon();

                            $userCoupon->user = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order = $orderID;
                            $userCoupon->save();
                            PlanOrder::create(
                                [
                                    'order_id'          => $orderID,
                                    'name'              => null,
                                    'email'             => null,
                                    'card_number'       => null,
                                    'card_exp_month'    => null,
                                    'card_exp_year'     => null,
                                    'plan_name'         => $plan->name,
                                    'plan_id'           => $plan->id,
                                    'price'             => $get_amount == null ? 0 : $get_amount,
                                    'price_currency'    => $currency,
                                    'txn_id'            => '',
                                    'payment_type'      => __('CinetPay'),
                                    'payment_status'    => 'succeeded',
                                    'receipt'           => null,
                                    'user_id'           => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id);
                            return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {

                if (
                    $currency != 'XOF' &&
                    $currency != 'CDF' &&
                    $currency != 'USD' &&
                    $currency != 'KMF' &&
                    $currency != 'GNF'
                ) {
                    return redirect()->route('plans.index')->with('error', __('Availabe currencies: XOF, CDF, USD, KMF, GNF'));
                }
                $call_back = route('plan.cinetpay.return') . '?_token=' . csrf_token();
                $returnURL = route('plan.cinetpay.notify') . '?_token=' . csrf_token();
                $cinetpay_data =  [
                    "amount"                => $get_amount,
                    "currency"              => $currency,
                    "apikey"                => $cinetpay_api_key,
                    "site_id"               => $cinetpay_site_id,
                    "transaction_id"        => $orderID,
                    "description"           => "Plan purchase",
                    "return_url"            => $call_back,
                    "notify_url"            => $returnURL,
                    "metadata"              => "user001",
                    'customer_name'         => isset($authuser->name) ? $authuser->name : 'Test',
                    'customer_surname'      => isset($authuser->name) ? $authuser->name : 'Test',
                    'customer_email'        => isset($authuser->email) ? $authuser->email : 'test@gmail.com',
                    'customer_phone_number' => isset($authuser->mobile_number) ? $authuser->mobile_number : '1234567890',
                    'customer_address'      => isset($authuser->address) ? $authuser->address  : 'A-101, alok area, USA',
                    'customer_city'         => 'texas',
                    'customer_country'      => 'BF',
                    'customer_state'        => 'USA',
                    'customer_zip_code'     => isset($authuser->zipcode) ? $authuser->zipcode : '432876',   
                ];

                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 45,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type:application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                //On recupère la réponse de CinetPay
                $response_body = json_decode($response, true);

                if (isset($response_body['code']) && $response_body['code'] == '201') {
                    $cinetpaySession = [
                        'order_id'      => $orderID,
                        'amount'        => $get_amount,
                        'plan_id'       => $plan->id,
                        'coupon_id'     => isset($coupons) && !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code'   => !empty($request->coupon) ? $request->coupon : '',
                    ];

                    $request->session()->put('cinetpaySession', $cinetpaySession);

                    $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
                    return redirect($payment_link);
                } else {
                    return back()->with('error', $response_body["description"]);
                }
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans.index')->with('error', $e->getMessage());
            }
            return view('plan.request', compact('stripe_session'));
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planCinetPayReturn(Request $request)
    {
        $cinetpaySession = $request->session()->get('cinetpaySession');
        $request->session()->forget('cinetpaySession');
        
        if (isset($request->transaction_id) || isset($request->token)) {
            $payment_setting = Utility::getAdminPaymentSetting();

            $cinetpay_check = [
                "apikey"            => $payment_setting['cinetpay_api_key'],
                "site_id"           => $payment_setting['cinetpay_site_id'],
                "transaction_id"    => $request->transaction_id ?? null
            ];
            
            $response       = $this->getPayStatus($cinetpay_check);
            $response_body  = json_decode($response, true);
            $authuser       = User::find(Auth::user()->id);
            $plan           = Plan::find($cinetpaySession['plan_id']);
            $getAmount      = $cinetpaySession['amount'];
            $currency       = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
            $orderID        = strtoupper(str_replace('.', '', uniqid('', true)));
            
            if (isset($response_body['code']) && $response_body['code'] == '00') {

                PlanOrder::create(
                    [
                        'order_id'          => $orderID,
                        'name'              => $authuser->name,
                        'email'             => $authuser->email,
                        'card_number'       => null,
                        'card_exp_month'    => null,
                        'card_exp_year'     => null,
                        'plan_name'         => $plan->name,
                        'plan_id'           => $plan->id,
                        'price'             => $getAmount,
                        'price_currency'    => $currency,
                        'txn_id'            => '',
                        'payment_type'      => __('Cinetpay'),
                        'payment_status'    => 'succeeded',
                        'receipt'           => null,
                        'user_id'           => $authuser->id,
                    ]
                );
                if ($request->coupon_code) {
                    $coupons = Coupon::find($request->coupon_id);

                    if (!empty($request->coupon_id)) {
                        if (!empty($coupons)) {
                            $userCoupon = new UserCoupon();
                            $userCoupon->user = $authuser->id;
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
                }

                $assignPlan = $authuser->assignPlan($plan->id);
                if ($assignPlan['is_success']) {
                    Utility::referralTransaction($plan);
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {

                return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
        }
    }

    public function planCinetPayNotify(Request $request)
    {
        /* 1- Recovery of parameters posted on the URL by CinetPay
         * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#les-etapes-pour-configurer-lurl-de-notification
         * */
        if (isset($request->cpm_trans_id)) {
            // Using your transaction identifier, check that the order has not yet been processed
            $VerifyStatusCmd = "1"; // status value to retrieve from your database
            if ($VerifyStatusCmd == '00') {
                //The order has already been processed
                // Scarred you script
                die();
            }
            $payment_setting = Utility::getAdminPaymentSetting();

            /* 2- Otherwise, we check the status of the transaction in the event of a payment attempt on CinetPay
            * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#2-verifier-letat-de-la-transaction */
            $cinetpay_check = [
                "apikey"            => $payment_setting['cinetpay_api_key'],
                "site_id"           => $payment_setting['cinetpay_site_id'],
                "transaction_id"    => $request->cpm_trans_id ?? null
            ];

            $response = $this->getPayStatus($cinetpay_check); // call query function to retrieve status

            //We get the response from CinetPay
            $response_body = json_decode($response, true);
            if (isset($response_body['code']) && $response_body['code'] == '00') {
                /* correct, on délivre le service
                 * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#3-delivrer-un-service*/
                echo 'Congratulations, your payment has been successfully completed';
            } else {
                // transaction a échoué
                echo 'Failure, code:' . $response_body['code'] . ' Description' . $response_body['description'] . ' Message: ' . $response_body['message'];
            }
            // Update the transaction in your database
            /*  $order->update(); */
        } else {
            print("cpm_trans_id non found");
        }
    }

    public function getPayStatus($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment/check',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type:application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return redirect()->back()->with('error', __('Something went wrong!'));
        } else {
            return ($response);
        }
    }

    public function storePayWithCinetPay(Request $request, $slug)
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

                    $cinetpay_api_key   = !empty($companyPaymentSetting['cinetpay_api_key']) ? $companyPaymentSetting['cinetpay_api_key'] : '';
                    $cinetpay_site_id   = !empty($companyPaymentSetting['cinetpay_site_id']) ? $companyPaymentSetting['cinetpay_site_id'] : '';
                    $currency           = isset($store['currency_code']) ? $store['currency_code'] : 'USD';
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    if (!empty(Auth::guard('customers')->user())) {
                        $customer   = Auth::guard('customers')->user();
                    } else {
                        $customer   = $cust_details;
                    }
                    try {
                        if (
                            $currency != 'XOF' &&
                            $currency != 'CDF' &&
                            $currency != 'USD' &&
                            $currency != 'KMF' &&
                            $currency != 'GNF'
                        ) {
                            return redirect()->back()->with('error', __('Availabe currencies: XOF, CDF, USD, KMF, GNF'));
                        }
                        $call_back = route('store.cinetpay.return') . '?_token=' . csrf_token();
                        $returnURL = route('store.cinetpay.notify') . '?_token=' . csrf_token();
                        $cinetpay_data =  [
                            "amount"                => $totalprice,
                            "currency"              => $currency,
                            "apikey"                => $cinetpay_api_key,
                            "site_id"               => $cinetpay_site_id,
                            "transaction_id"        => $orderID,
                            "description"           => "Product purchase",
                            "return_url"            => $call_back,
                            "notify_url"            => $returnURL,
                            "metadata"              => "user001",
                            'customer_name'         => isset($cust_details['name']) ? $cust_details['name'] : 'Test',
                            'customer_surname'      => isset($cust_details['name']) ? $cust_details['name'] : 'Test',
                            'customer_email'        => isset($cust_details['email']) ? $cust_details['email'] : 'test@gmail.com',
                            'customer_phone_number' => isset($cust_details['phone']) ? $cust_details['phone'] : '1234567890',
                            'customer_address'      => isset($cust_details['billing_address']) || isset($cust_details['shipping_address']) ? $cust_details['billing_address'] . ' ' . $cust_details['shipping_address'] : 'A-101, alok area, USA',
                            'customer_city'         => 'texas',
                            'customer_country'      => 'BF',
                            'customer_state'        => 'USA',
                            'customer_zip_code'     => isset($customer['zipcode']) ? $customer['zipcode'] : '432876',   
                        ];
        
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 45,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_HTTPHEADER => array(
                                "content-type:application/json"
                            ),
                        ));
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);
        
                        //On recupère la réponse de CinetPay
                        $response_body = json_decode($response, true);
        
                        if (isset($response_body['code']) && $response_body['code'] == '201') {
                            $cinetpaySession = [
                                'slug'          => $slug, 
                                'amount'        => $totalprice, 
                                'product_id'    => $product_id,
                            ];
        
                            $request->session()->put('cinetpaySession', $cinetpaySession);
        
                            $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
                            return redirect($payment_link);
                        } else {
                            return back()->with('error', $response_body["description"]);
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', $e->getMessage());
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
    
    public function storeCinetPayReturn(Request $request)
    {
        $cinetpaySession = $request->session()->get('cinetpaySession');
        $request->session()->forget('cinetpaySession');

        if (isset($request->transaction_id) || isset($request->token)) {
            $slug                   = $cinetpaySession['slug'];
            $store                  = Store::where('slug', $slug)->first();
            $companyPaymentSetting  = Utility::getPaymentSetting($store->id);

            $cinetpay_check = [
                "apikey"            => $companyPaymentSetting['cinetpay_api_key'],
                "site_id"           => $companyPaymentSetting['cinetpay_site_id'],
                "transaction_id"    => $request->transaction_id ?? null
            ];

            $response       = $this->getPayStatus($cinetpay_check);
            $response_body  = json_decode($response, true);
            $getAmount      = $cinetpaySession['amount'];
            $product_id     = $cinetpaySession['product_id'];
            if (isset($response_body['code']) && $response_body['code'] == '00') {
                try {
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
                    $order->payment_type    = 'Cinetpay';
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
                    if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                        $dArr = [
                            'order_name' => $order->name,
                        ];
                        $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                        $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
                    }
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
            } else {
                return redirect()->back()->with('error', __('Your Payment has failed!'));
            }
        } else {
            return redirect()->back()->with('error', __('Your Payment has failed!'));
        }
    }

    public function storeCinetPayNotify(Request $request)
    {
        /* 1- Recovery of parameters posted on the URL by CinetPay
         * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#les-etapes-pour-configurer-lurl-de-notification
         * */
        if (isset($request->cpm_trans_id)) {
            // Using your transaction identifier, check that the order has not yet been processed
            $VerifyStatusCmd = "1"; // status value to retrieve from your database
            if ($VerifyStatusCmd == '00') {
                //The order has already been processed
                // Scarred you script
                die();
            }
            $cinetpaySession = $request->session()->get('cinetpaySession');
            $request->session()->forget('cinetpaySession');
            $slug                   = $cinetpaySession['slug'];
            $store                  = Store::where('slug', $slug)->first();
            $companyPaymentSetting  = Utility::getPaymentSetting($store->id);

            /* 2- Otherwise, we check the status of the transaction in the event of a payment attempt on CinetPay
            * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#2-verifier-letat-de-la-transaction */
            $cinetpay_check = [
                "apikey"            => $companyPaymentSetting['cinetpay_api_key'],
                "site_id"           => $companyPaymentSetting['cinetpay_site_id'],
                "transaction_id"    => $request->cpm_trans_id ?? null
            ];

            $response = $this->getPayStatus($cinetpay_check); // call query function to retrieve status

            //We get the response from CinetPay
            $response_body = json_decode($response, true);
            if (isset($response_body['code']) && $response_body['code'] == '00') {
                /* correct, on délivre le service
                 * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#3-delivrer-un-service*/
                echo 'Congratulations, your payment has been successfully completed';
            } else {
                // transaction a échoué
                echo 'Failure, code:' . $response_body['code'] . ' Description' . $response_body['description'] . ' Message: ' . $response_body['message'];
            }
            // Update the transaction in your database
            /*  $order->update(); */
        } else {
            print("cpm_trans_id non found");
        }
    }
}
