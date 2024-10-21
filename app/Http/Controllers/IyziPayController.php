<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\ProductCoupon;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Exception;

class IyziPayController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $iyzipay_api_key = $adminPaymentSettings['iyzipay_api_key'];
        $iyzipay_secret_key = $adminPaymentSettings['iyzipay_secret_key'];
        $iyzipay_mode = $adminPaymentSettings['iyzipay_mode'];
        $currency = !empty($adminPaymentSettings['currency']) ? $adminPaymentSettings['currency'] : 'USD';
        $plan = Plan::find($planID);
        $coupon_id = '0';
        $price = $plan->price;
        $coupon_code = null;
        $discount_value = null;
        $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if ($coupons) {
            $coupon_code = $coupons->code;
            $usedCoupun     = $coupons->used_coupon();
            if ($coupons->limit == $usedCoupun) {
                $res_data['error'] = __('This coupon code has expired.');
            } else {
                $discount_value = ($plan->price / 100) * $coupons->discount;
                $price  = $price - $discount_value;
                if ($price < 0) {
                    $price = $plan->price;
                }
                $coupon_id = $coupons->id;
            }
        }
        $res_data['total_price'] = $price;
        $res_data['coupon']      = $coupon_id;
        // set your Iyzico API credentials
        try {
            $setBaseUrl = ($iyzipay_mode == 'sandbox') ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
            $options = new \Iyzipay\Options();
            $options->setApiKey($iyzipay_api_key);
            $options->setSecretKey($iyzipay_secret_key);
            $options->setBaseUrl($setBaseUrl); // or "https://api.iyzipay.com" for production
            $ipAddress = Http::get('https://ipinfo.io/?callback=')->json();
            $address = ($authuser->address) ? $authuser->address : 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1';
            // create a new payment request
            $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
            $request->setLocale('en');
            $request->setPrice($res_data['total_price']);
            $request->setPaidPrice($res_data['total_price']);
            $request->setCurrency($currency);
            $request->setCallbackUrl(route('iyzipay.payment.callback', [$plan->id, $price, $coupon_code]));
            $request->setEnabledInstallments(array(1));
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId($authuser->id);
            $buyer->setName(explode(' ', $authuser->name)[0]);
            $buyer->setSurname(explode(' ', $authuser->name)[0]);
            $buyer->setGsmNumber("+" . $authuser->dial_code . $authuser->phone);
            $buyer->setEmail($authuser->email);
            $buyer->setIdentityNumber(rand(0, 999999));
            $buyer->setLastLoginDate("2023-03-05 12:43:35");
            $buyer->setRegistrationDate("2023-04-21 15:12:09");
            $buyer->setRegistrationAddress($address);
            $buyer->setIp($ipAddress['ip']);
            $buyer->setCity($ipAddress['city']);
            $buyer->setCountry($ipAddress['country']);
            $buyer->setZipCode($ipAddress['postal']);
            $request->setBuyer($buyer);
            $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName($authuser->name);
            $shippingAddress->setCity($ipAddress['city']);
            $shippingAddress->setCountry($ipAddress['country']);
            $shippingAddress->setAddress($address);
            $shippingAddress->setZipCode($ipAddress['postal']);
            $request->setShippingAddress($shippingAddress);
            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName($authuser->name);
            $billingAddress->setCity($ipAddress['city']);
            $billingAddress->setCountry($ipAddress['country']);
            $billingAddress->setAddress($address);
            $billingAddress->setZipCode($ipAddress['postal']);
            $request->setBillingAddress($billingAddress);
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("BI101");
            $firstBasketItem->setName("Binocular");
            $firstBasketItem->setCategory1("Collectibles");
            $firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($res_data['total_price']);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);

            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
            return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('errors', $e->getMessage());
        }
    }

    public function iyzipayCallback(Request $request, $planID, $price, $coupanCode = null)
    {
        $plan = Plan::find($planID);
        $user = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $order = new PlanOrder();
        $order->order_id = time();
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $price;
        $order->price_currency = !empty($adminPaymentSettings['currency']) ? $adminPaymentSettings['currency'] : 'USD';
        $order->txn_id = time();
        $order->payment_type = __('Iyzipay');
        $order->payment_status = 'succeeded';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();
        $user = User::find($user->id);
        $coupons = Coupon::where('code', $coupanCode)->where('is_active', '1')->first();
        if (!empty($coupons)) {
            $userCoupon         = new UserCoupon();
            $userCoupon->user   = $user->id;
            $userCoupon->coupon = $coupons->id;
            $userCoupon->order  = $order->order_id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit <= $usedCoupun) {
                $coupons->is_active = 0;
                $coupons->save();
            }
        }
        $assignPlan = $user->assignPlan($plan->id);
        Utility::referralTransaction($plan);


        if ($assignPlan['is_success']) {
            return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
        }
    }



    public function iyzipaypayment(Request $request, $slug)
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

        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            try {
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

                $products = $cart;

                $storePaymentSetting = Utility::getPaymentSetting($store->id);
                $iyzipay_api_key = $storePaymentSetting['iyzipay_api_key'];
                $iyzipay_secret_key = $storePaymentSetting['iyzipay_secret_key'];
                $iyzipay_mode = $storePaymentSetting['iyzipay_mode'];
                $currency = $store->currency_code;
                $total_tax = $sub_total = $total = $sub_tax = 0;
                $product_name = [];

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



                    $address = !empty($cust_details['billing_address']) ? $cust_details['billing_address'] : '';

                    $setBaseUrl = ($iyzipay_mode == 'sandbox') ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
                    $options = new \Iyzipay\Options();
                    $options->setApiKey($iyzipay_api_key);
                    $options->setSecretKey($iyzipay_secret_key);
                    $options->setBaseUrl($setBaseUrl); // or "https://api.iyzipay.com" for production
                    $ipAddress = Http::get('https://ipinfo.io/?callback=')->json();
                    $address = ($address) ? $address : 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1';
                    // create a new payment request
                    $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
                    $request->setLocale('en');
                    $request->setPrice($price);
                    $request->setPaidPrice($price);
                    $request->setCurrency($currency);
                    $request->setCallbackUrl(route('iyzipay.callback', [$slug, $price]));
                    $request->setEnabledInstallments(array(1));
                    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
                    $buyer = new \Iyzipay\Model\Buyer();
                    $buyer->setId(!empty($cust_details['id']) ? $cust_details['id'] : '0');
                    $buyer->setName(!empty($cust_details['name']) ? $cust_details['name'] : 'Guest');
                    $buyer->setSurname(!empty($cust_details['name']) ? $cust_details['name'] : 'Guest');
                    $buyer->setGsmNumber("+" . !empty($cust_details['phone']) ? $cust_details['phone'] : '9999999999');
                    $buyer->setEmail(!empty($cust_details['email']) ? $cust_details['email'] : 'test@gmail.com');
                    $buyer->setIdentityNumber(rand(0, 999999));
                    $buyer->setLastLoginDate("2023-03-05 12:43:35");
                    $buyer->setRegistrationDate("2023-04-21 15:12:09");
                    $buyer->setRegistrationAddress($address);
                    $buyer->setIp($ipAddress['ip']);
                    $buyer->setCity($ipAddress['city']);
                    $buyer->setCountry($ipAddress['country']);
                    $buyer->setZipCode($ipAddress['postal']);
                    $request->setBuyer($buyer);
                    $shippingAddress = new \Iyzipay\Model\Address();
                    $shippingAddress->setContactName(!empty($cust_details['name']) ? $cust_details['name'] : 'Guest');
                    $shippingAddress->setCity($ipAddress['city']);
                    $shippingAddress->setCountry($ipAddress['country']);
                    $shippingAddress->setAddress($address);
                    $shippingAddress->setZipCode($ipAddress['postal']);
                    $request->setShippingAddress($shippingAddress);
                    $billingAddress = new \Iyzipay\Model\Address();
                    $billingAddress->setContactName(!empty($cust_details['name']) ? $cust_details['name'] : 'Guest');
                    $billingAddress->setCity($ipAddress['city']);
                    $billingAddress->setCountry($ipAddress['country']);
                    $billingAddress->setAddress($address);
                    $billingAddress->setZipCode($ipAddress['postal']);
                    $request->setBillingAddress($billingAddress);
                    $basketItems = array();
                    $firstBasketItem = new \Iyzipay\Model\BasketItem();
                    $firstBasketItem->setId("BI101");
                    $firstBasketItem->setName("Binocular");
                    $firstBasketItem->setCategory1("Collectibles");
                    $firstBasketItem->setCategory2("Accessories");
                    $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                    $firstBasketItem->setPrice($price);
                    $basketItems[0] = $firstBasketItem;
                    $request->setBasketItems($basketItems);

                    $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
                    return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
                } else {
                    return redirect()->back()->with('error', __('product is not found.'));
                }
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('You Need To Login'));
        }
    }



    public function iyzipaypaymentCallback(Request $request, $slug, $price)
    {
        $cart     = session()->get($slug);
        $store        = Store::where('slug', $slug)->first();
        $cust_details = $cart['cust_details'];
        $shipping_data = $cart['shipping_data'];
        $product_id = $cart['product_id'];
        $totalprice = $cart['totalprice'];
        $coupon = $cart['coupon_json'];
        $products = $cart['all_products'];


        if (isset($cart['coupon']['data_id'])) {
            $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
        } else {
            $coupon = '';
        }
        if ($products) {
            try {
                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
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
                $order->price           = $totalprice;
                $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json     = json_encode($coupon);
                $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->product         = json_encode($products);
                $order->price_currency  = $store->currency_code;
                $order->txn_id          = time();;
                $order->payment_type    = 'Iyzipay';
                $order->payment_status  = 'approved';
                $order->receipt         = '';
                $order->user_id         = $store['id'];
                $order->customer_id     = isset($customer->id) ? $customer->id : '';
                $order->save();

                //webhook
                $module = 'New Order';
                $webhook =  Utility::webhook($module, $store['id']);
                if ($webhook) {
                    $parameter = json_encode($products);
                    //
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
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
                $msg = redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        Crypt::encrypt($order->id),
                    ]
                )->with('success', __('Transaction has been success'));

                session()->forget($slug);

                return $msg;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
        }
    }
}
