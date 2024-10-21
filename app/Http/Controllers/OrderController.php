<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Mail\OrderDeliveredMail;
use App\Models\Mail\OrderMail;
use App\Models\Order;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Store;
use App\Models\UserDetail;
use App\Models\Utility;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\PlanOrder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Orders')) {
            if (Auth::user()->type == 'super admin') {
                $user  = \Auth::user();
                $store = Store::where('id', $user->current_store)->first();

                $orders = Order::orderBy('id', 'DESC')->get();
            } else {
                $user  = \Auth::user();
                $store = Store::where('id', $user->current_store)->first();

                $orders = Order::orderBy('id', 'DESC')->where('user_id', $store->id)->get();
            }
            return view('orders.index', compact('orders', "store"));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function export($id)
    {
        $name = 'Orders_' . date('Y-m-d i:h:s');
        $data = Excel::download(new OrdersExport($id), $name . '.xlsx');

        return $data;
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {
        if (\Auth::user()->can('Show Orders')) {
            $id    = Crypt::decrypt($order);
            $order = Order::find($id);

            $store = Store::where('id', $order->user_id)->first();

            $user_details = UserDetail::where('id', $order->user_address_id)->first();

            if (!empty($order->shipping_data)) {
                $shipping_data = json_decode($order->shipping_data);
                $location_data = Location::where('id', $shipping_data->location_id)->first();
            } else {
                $shipping_data = '';
                $location_data = '';
            }

            $order_products = json_decode($order->product);
            $sub_total      = 0;
            if (!empty($order_products)) {
                $grand_total = 0;
                $total_taxs  = 0;
                foreach ($order_products->products as $product) {
                    if (isset($product->variant_id) && $product->variant_id != 0) {
                        if (!empty($product->tax)) {
                            foreach ($product->tax as $tax) {
                                $sub_tax    = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                                $total_taxs += $sub_tax;
                            }
                        }

                        $totalprice  = $product->variant_price * $product->quantity + $total_taxs;
                        $subtotal    = $product->variant_price * $product->quantity;
                        $sub_total   += $subtotal;
                        $grand_total += $totalprice;
                    } else {
                        if (!empty($product->tax)) {
                            foreach ($product->tax as $tax) {
                                $sub_tax    = ($product->price * $product->quantity * $tax->tax) / 100;
                                $total_taxs += $sub_tax;
                            }
                        }

                        $totalprice  = $product->price * $product->quantity + $total_taxs;
                        $subtotal    = $product->price * $product->quantity;
                        $sub_total   += $subtotal;
                        $grand_total += $totalprice;
                    }
                }
            }

            $discount_value = 0;
            $plan_price     = 0;
            if (!empty($order->coupon_json)) {
                $coupons = json_decode($order->coupon_json);
                if (!empty($coupons)) {
                    if ($coupons->enable_flat == 'on') {
                        $discount_value = $coupons->flat_discount;
                    } else {
                        $discount_value = ($grand_total / 100) * $coupons->discount;
                    }
                }

                $plan_price = $grand_total - $discount_value;
            }
            $order_id = Crypt::encrypt($order->id);

            return view('orders.view', compact('order', 'store', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'order_id', 'shipping_data', 'location_data', 'plan_price', 'discount_value'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $store = Store::where('id', $order->user_id)->first();
        //  order is Cancel
        if ($request->delivered == 'Cancel Order' && $order['status'] != "Cancel Order") {
            $Products_order = json_decode($order->product);

            foreach ($Products_order->products as $PurchasedProduct) {
                $product = Product::where('id', $PurchasedProduct->product_id)->first();
                $product->quantity = $product->quantity + $PurchasedProduct->quantity;
                $product->save();
            }
        }

        // order is delivered
        if ($request->delivered == 'delivered' && $order['status'] == "Cancel Order") {
            $Products_order = json_decode($order->product);
            foreach ($Products_order->products as $PurchasedProduct) {

                $product = Product::where('id', $PurchasedProduct->product_id)->first();

                $product->quantity = $product->quantity - $PurchasedProduct->quantity;

                $product->save();
            }
        }
        $order['status'] = $request->delivered;
        $order->update();

        //webhook
        $module = 'Status Change';
        $current_store = \Auth::user()->current_store;
        $webhook =  Utility::webhook($module, $current_store);
        if ($webhook) {
            $parameter = json_encode($order->product);
            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
            $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
            if ($status != true) {
                $msgs  = 'Webhook call failed.';
            }
        }

        $order_email = $order->email;

        // if (isset($store->mail_driver) && !empty($store->mail_driver)) {

            $dArr  = [
                'order_name' => $order['name'],
                'order_status' => $order['status'],
            ];
            $order = Crypt::encrypt($order->id);

            try {

                $resp  = Utility::sendEmailTemplate('Status Change', $order_email, $dArr, $store, $order);
            } catch (\Throwable $th) {
                throw $th;
            }
            // dd($resp);
            if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                $order = Order::find(Crypt::decrypt($order));
                $customer = Customer::where('id', $order->customer_id)->first();
                if (is_null($customer)) {
                    $customer = new \stdClass();
                    $customer->phone_number = $order->phone;
                }
                Utility::status_change_customer($order, $customer, $store);
            }
        // }

        return response()->json(
            [
                'success' => __('Successfully Updated.') . ((isset($msgs)) ? '<br> <span class="text-danger">' . $msgs . '</span>' : '') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
            ]
        );
    }


    public function delete_order_item($id, $variant_id = 0, $order_id, $key)
    {
        if (\Auth::user()->can('Delete Orders')) {
            $order_item = Order::where('order_id', '#' . $order_id)->first();

            $order_json =  json_decode($order_item->product, true);
            if (array_key_exists($key, $order_json['products'])) {
                unset($order_json['products'][$key]);
            }

            if (count($order_json['products']) <= 0) {
                $order_item->delete();
                return redirect()->route('orders.index')->with('success', __('Order Item Deleted!'));
            }

            $order_item->product = json_encode($order_json);
            $order_item->update();

            return redirect()->back()->with(
                'success',
                __('Order Item Deleted!')
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */



    public function destroy(Order $order)
    {
        if (\Auth::user()->can('Delete Orders')) {
            $order->delete();

            return redirect()->back()->with(
                'success',
                __('Order Successfully Deleted!')
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function delete_plan_order(PlanOrder $order_id)
    {
        if (\Auth::user()->can('Delete Plan Order')) {
            $id = $order_id->id;
            $order = PlanOrder::where('id', $id);

            $order->delete();

            return redirect()->back()->with(
                'success',
                __('Order Successfully Deleted!')
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function receipt($id)
    {
        $order = Order::find($id);
        $store = Store::where('id', $order->user_id)->first();

        if (!empty($order->shipping_data)) {
            $shipping_data = json_decode($order->shipping_data);
            $location_data = Location::where('id', $shipping_data->location_id)->first();
        } else {
            $shipping_data = '';
            $location_data = '';
        }

        $user_details = UserDetail::where('id', $order->user_address_id)->first();

        $order_products = json_decode($order->product);
        $sub_total      = 0;
        if (!empty($order_products)) {
            $grand_total = 0;
            $total_taxs  = 0;
            foreach ($order_products->products as $k => $product) {
                if (isset($product->variant_id) && $product->variant_id != 0) {
                    if (!empty($product->tax)) {
                        foreach ($product->tax as $tax) {
                            $sub_tax    = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }
                    $totalprice  = $product->variant_price * $product->quantity + $total_taxs;
                    $subtotal    = $product->variant_price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                } else {
                    if (!empty($product->tax)) {
                        foreach ($product->tax as $tax) {
                            $sub_tax    = ($product->price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }

                    $totalprice  = $product->price * $product->quantity + $total_taxs;
                    $subtotal    = $product->price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                }
            }
        }
        $discount_value = 0;
        $plan_price     = 0;
        if (!empty($order->coupon_json)) {
            $coupons = json_decode($order->coupon_json);
            if (!empty($coupons)) {
                if ($coupons->enable_flat == 'on') {
                    $discount_value = $coupons->flat_discount;
                } else {
                    $discount_value = ($grand_total / 100) * $coupons->discount;
                }
            }

            $plan_price = $grand_total - $discount_value;
        }

        $order_id = Crypt::encrypt($order->id);

        return view('orders.receipt', compact('order', 'store', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'order_id', 'shipping_data', 'location_data', 'discount_value', 'plan_price'));
    }
}
