<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\UserDetail;
use App\Models\UserStore;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Rest\ApiContext;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

use App\Models\ProductCoupon;
use App\Models\PurchasedProducts;
use App\Models\User;
use PayPal\Exception\PayPalConnectionException;
use Illuminate\Support\Facades\Mail;

class PaypalController extends Controller
{
    private $_api_context;
    public $paypal_client_id;
    public $paypal_mode;
    public $paypal_secret_key;
    public $currancy_symbol;
    public $currancy;




    public function paymentConfig($slug = '')
    {
        if (Auth::check() && Utility::CustomerAuthCheck($slug) == false) {
            $payment_setting = Utility::getAdminPaymentSetting();
        } else {
            $store                           = Store::where('slug', $slug)->first();
            $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData->created_by);
        }

        if ($payment_setting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }
    }

    public function PayWithPaypal(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {

            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return redirect()->route('store.slug', $slug)->with('error', __('Please select shipping.'));
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
                return redirect()->route('store.slug', $slug)->with('error', __('All field is required.'));
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
            $total        = 0;
            $sub_tax      = 0;
            $sub_total    = 0;
            $total_tax    = 0;
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
                // try
                // {
                $coupon_id = null;
                if (!empty($request->shipping_id)) {
                    $shipping = Shipping::find($request->shipping_id);
                    if (!empty($shipping)) {
                        $totalprice     = $totalprice + $shipping->price;
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


                $price = str_replace(' ', '', str_replace(',', '', str_replace($store->currency, '', $request->total_price)));
                $cart['cust_details'] = $cust_details;
                $cart['shipping_data'] = $shipping_data;
                $cart['product_id'] = $product_id;
                $cart['all_products'] = $products;
                $cart['totalprice'] = $price;
                $cart['coupon_id'] = $request->coupon_id;
                $cart['coupon_json'] = json_encode($coupon);
                $cart['dicount_price'] = $request->dicount_price;
                $cart['currency_code'] = $store->currency_code;
                $cart['user_id'] = $store['id'];
                session()->put($slug, $cart);

                // $this->setApiContext($slug);
                // $this->paymentconfig($slug);
                $admin_payments_details = Utility::getPaymentSetting($store->id);
                config(
                    [
                        'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                        'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                        'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
                    ]
                );
                $provider = new PayPalClient;

                $provider->setApiCredentials(config('paypal'));
                $paypalToken = $provider->getAccessToken();

                Session::put('paypal_payment_id', $paypalToken['access_token']);
                $objUser = \Auth::user();

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('get.payment.status', $store->slug),
                        "cancel_url" => route('get.payment.status', $store->slug),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $price,
                            ],
                        ],
                    ],
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->back()
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->back()
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
                return redirect()->back()->with('error', __('Unknown error occurred'));
                // }
                // catch(\Exception $e)
                // {
                //     return redirect()->back()->with('error', __('Unknown error occurred'));
                // }
            } else {
                return redirect()->back()->with('error', __('is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('You need to login'));
        }
    }

    public function GetPaymentStatus(Request $request, $slug)
    {

        $cart = session()->get($slug);

        $products     = $cart['products'];
        $store        = Store::where('slug', $slug)->first();

        $total        = 0;
        $new_qty      = 0;
        $sub_total    = 0;
        $total_tax    = 0;
        $product_name = [];
        $product_id   = [];
        $quantity     = [];
        $pro_tax      = [];

        foreach ($products as $key => $product) {
            if ($product['variant_id'] != 0) {
                $new_qty                = $product['originalvariantquantity'] - $product['quantity'];
                $product_edit           = ProductVariantOption::find($product['variant_id']);
                $product_edit->quantity = $new_qty;
                $product_edit->save();

                $product_name[] = $product['product_name'];
                $product_id[]   = $key;
                $quantity[]     = $product['quantity'];


                foreach ($product['tax'] as $tax) {
                    $sub_tax   = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                    $pro_tax[] = $sub_tax;
                }
                $totalprice = $product['variant_price'] * $product['quantity'] + $total_tax;
                $subtotal   = $product['variant_price'] * $product['quantity'];
                $sub_total  += $subtotal;
                $total      += $totalprice;
            } else {
                $new_qty                = $product['originalquantity'] - $product['quantity'];
                $product_edit           = Product::find($product['product_id']);
                $product_edit->quantity = $new_qty;
                $product_edit->save();

                $product_name[] = $product['product_name'];
                $product_id[]   = $key;
                $quantity[]     = $product['quantity'];


                foreach ($product['tax'] as $tax) {
                    $sub_tax   = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                    $pro_tax[] = $sub_tax;
                }
                $totalprice = $product['price'] * $product['quantity'] + $total_tax;
                $subtotal   = $product['price'] * $product['quantity'];
                $sub_total  += $subtotal;
                $total      += $totalprice;
            }
        }
        if (isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping'])) {
            $shipping = Shipping::find($cart['shipping']['shipping_id']);
            if (!empty($shipping)) {
                $shipping_name  = $shipping->name;
                $shipping_price = $shipping->price;

                $shipping_data = json_encode(
                    [
                        'shipping_name' => $shipping_name,
                        'shipping_price' => $shipping_price,
                        'location_id' => $cart['shipping']['location_id'],
                    ]
                );
            } else {
                $shipping_data = '';
            }
        }
        $user = Auth::user();

        if ($product) {
            // $this->setApiContext($slug);
            $admin_payments_details = Utility::getPaymentSetting($store->id);

            config(
                [
                    'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                    'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                    'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
                ]
            );
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            $payment_id = Session::get('paypal_payment_id');

            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

            // try
            // {
            // $result = $payment->execute($execution, $this->_api_context)->toArray();

            $order          = new Order();
            $order->user_id = Auth()->id();
            $latestOrder    = Order::orderBy('created_at', 'DESC')->first();
            if (!empty($latestOrder)) {
                $order->order_nr = '#' . str_pad($latestOrder->id + 1, 4, "100", STR_PAD_LEFT);
            } else {
                $order->order_nr = '#' . str_pad(1, 4, "100", STR_PAD_LEFT);
            }
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                if ($response['status'] == 'COMPLETED') {

                    $statuses = 'success';
                }
                $cart     = session()->get($slug);
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
                $order->payment_type    = __('PAYPAL');
                $order->payment_status  = 'approved';
                $order->receipt         = '';
                $order->user_id         = $cart['user_id'];
                $order->customer_id     = isset($customer->id) ? $customer->id : '';
                $order->save();


                if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {
                    foreach ($products['products'] as $k_pro => $product_id) {

                        $purchased_product = new PurchasedProducts();
                        $purchased_product->product_id  = $product_id['product_id'];
                        $purchased_product->customer_id = $customer->id;
                        $purchased_product->order_id   = $order->id;
                        $purchased_product->save();
                    }
                }

                $order_email = $order->email;
                $owner = User::find($store->created_by);
                $owner_email = $owner->email;
                $order_id = Crypt::encrypt($order->id);
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
                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        Crypt::encrypt($order->id),
                    ]
                )->with('success', __('Transaction has been ' . __($statuses)));
            } else {
                return redirect()->back()->with('error', __(
                    'Transaction has been failed.'
                ));
            }
            // }
            // catch(\Exception $e)
            // {
            //     return redirect()->back()->with('error', __('Transaction has been failed.'));
            // }
        } else {
            return redirect()->back()->with('error', __(' is deleted.'));
        }
    }

    public function planPayWithPaypal(Request $request)
    {
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);
        $this->paymentconfig();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $get_amount = $plan->price;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        if ($plan) {
            try {

                $coupon_id = null;
                $price     = $plan->price;
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price          = $plan->price - $discount_value;
                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $coupon_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $get_amount = $price;
                $coupons = Coupon::find($coupon_id);
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
                        $planorder->name = $user->name;
                        $planorder->email = $user->email;
                        $planorder->card_number = null;
                        $planorder->card_exp_month = null;
                        $planorder->card_exp_year = null;
                        $planorder->plan_name = $plan->name;
                        $planorder->plan_id = $plan->id;
                        $planorder->price = $price == null ? 0 : $price;
                        $planorder->price_currency = !empty($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : 'USD';
                        $planorder->txn_id = '';
                        $planorder->payment_type = 'PAYPAL';
                        $planorder->payment_status = 'succeeded';
                        $planorder->receipt = NULL;
                        $planorder->user_id = $user->id;
                        $planorder->store_id = $user->current_store;
                        $planorder->save();

                        return redirect()->route('plans.index')->with('success', __("Plan Successfully Activated"));
                    }
                } else {
                    $this->paymentConfig();
                    $paypalToken = $provider->getAccessToken();
                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "application_context" => [
                            "return_url" => route('get.store.payment.status', [$plan->id, $get_amount]),
                            "cancel_url" =>  route('get.store.payment.status', [$plan->id, $get_amount]),
                        ],
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => Utility::getValByName('site_currency'),
                                    "value" => $get_amount
                                ]
                            ]
                        ]
                    ]);
                    if (isset($response['id']) && $response['id'] != null) {
                        // redirect to approve href
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                return redirect()->away($links['href']);
                            }
                        }
                        return redirect()
                            ->route('plans.index')
                            ->with('error', 'Something went wrong.');
                    } else {
                        return redirect()
                            ->route('plans.index')
                            ->with('error', $response['message'] ?? 'Something went wrong.');
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function storeGetPaymentStatus(Request $request, $plan_id, $amount)
    {
        $user     = Auth::user();
        $store_id = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan = Plan::find($plan_id);

        if ($plan) {
            $this->paymentConfig();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($coupons)) {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $order_id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                if ($response['status'] == 'COMPLETED') {
                    $statuses = 'succeeded';
                }
                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->email           = $user->email;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $amount;
                $planorder->price_currency = !empty($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : 'USD';
                $planorder->txn_id         = '';
                $planorder->payment_type   = __('PAYPAL');
                $planorder->payment_status = $statuses;
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

                $assignPlan = $user->assignPlan($plan->id);
                Utility::referralTransaction($plan);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {


                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
                return redirect()->route('plans.index')->with('error', __('Transaction has been ' . __($statuses)));
            } else {
                return redirect()
                    ->route('plans.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
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
}
