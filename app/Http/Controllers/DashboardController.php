<?php

namespace App\Http\Controllers;

use App\Models\CustomDomainRequest;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Store;
use App\Models\Stream;
use App\Models\Task;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserStore;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Constraint\Count;
use Spatie\Permission\Models\Role;
use Stripe;
use File;
use Nwidart\Modules\Facades\Module;

class DashboardController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }
        $uri = url()->full();
        $segments = explode('/', str_replace('' . url('') . '', '', $uri));
        $segments = $segments[1] ?? null;

        if ($segments == null) {
            $local = parse_url(config('app.url'))['host'];

            // Get the request host
            $remote = request()->getHost();
            // Get the remote domain

            // remove WWW
            $remote = str_replace('www.', '', $remote);

            if ($local != $remote){
                $domain = CustomDomainRequest::where('status','1')->where('custom_domain',$remote)->first();
                // If the domain exists
                if(isset($domain) && !empty($domain)) {
                    $store = Store::find($domain->store_id);
                    if($store && $store['domain_switch'] == 'on') {
                        $store_slug = $store->slug;
                        return app('App\Http\Controllers\StoreController')->storeSlug($store_slug);
                    }
                } else {
                    $sub_store = Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
                    if ($sub_store && $sub_store->enable_subdomain == 'on') {
                        return app('App\Http\Controllers\StoreController')->storeSlug($sub_store->slug);
                    } else {
                        return abort('404', 'Not Found');
                    }
                }
            }
        }

        if (\Auth::check()) {
            if (\Auth::user()->can('Manage Dashboard')) {
                if (\Auth::user()->type != 'super admin') {
                    $store      = Auth::user();
                    $newproduct = Product::where('store_id', $store->current_store)->count();
                    $products   = Product::where('store_id', $store->current_store)->limit(5)->get();
                    $new_orders = Order::where('user_id', $store->current_store)->limit(5)->orderBy('id', 'DESC')->get();
                    $orders     = Order::where('user_id', $store->current_store)->get();
                    $chartData  = $this->getOrderChart(['duration' => 'week']);
                    $store_id   = Store::where('id', $store->current_store)->first();
                    if ($store_id) {
                        if ($store_id['enable_storelink'] == 'on') {
                            $app_url = trim(env('APP_URL'), '/');
                            $store_id['store_url'] = $app_url . '/store/' . $store_id['slug'];
                        } else if ($store_id['enable_domain'] == 'on') {
                            $store_id['store_url'] = 'https://' . $store_id['domains'] . '/';
                        } else {
                            $store_id['store_url'] = 'https://' . $store_id['subdomain'] . '/';
                        }
                    }


                    $totle_sale  = 0;
                    $totle_order = 0;

                    if (!empty($orders)) {
                        $pro_qty   = 0;
                        $item_id   = [];
                        $totle_qty = [];
                        foreach ($orders as $order) {
                            $order_array = json_decode($order->product);

                            $pro_id      = [];
                            if (!empty($order_array->products)) {

                                foreach ($order_array->products as $key => $item) {
                                    if (!in_array($item->id, $item_id)) {
                                        $item_id[]   = $item->id;
                                        $totle_qty[] = $item->quantity;
                                    } else {
                                        $totle_qty[array_search($item->id, $item_id)] += $item->quantity;
                                    }
                                }
                            }
                            $totle_sale += $order['price'];
                            $totle_order++;
                        }
                    }

                    $users = Auth::user();
                    $plan = Plan::find($users->plan);
                    if (isset($plan->storage_limit) > 0) {
                        $storage_limit = ($users->storage_limit / $plan->storage_limit) * 100;
                    } else {
                        $storage_limit = 0;
                    }

                    return view('home', compact('products', 'store_id', 'users', 'plan', 'storage_limit', 'totle_sale', 'store', 'orders', 'totle_order', 'newproduct', 'item_id', 'totle_qty', 'chartData', 'new_orders'));
                } else {
                    $user                       = \Auth::user();
                    $user['total_user']         = $user->countCompany();
                    $user['total_paid_user']    = $user->countPaidCompany();
                    $user['total_orders']       = Order::total_orders();
                    $user['total_orders_price'] = Order::total_orders_price();
                    $user['total_plan_price']   = PlanOrder::total_plan_price();
                    $user['total_plan']         = Plan::total_plan();
                    $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '-');
                    $chartData                  = $this->getOrderChart(['duration' => 'week']);

                    return view('home', compact('user', 'chartData'));
                }
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on' && \Schema::hasTable('landing_page_settings')) {
                    return view('landingpage::layouts.landingpage');
                } else {
                    return redirect('login');
                }
            }
        }
    }

    public function getOrderChart($arrParam)
    {
        $user        = Auth::user();
        $userstore   = UserStore::where('store_id', $user->current_store)->first();
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach ($arrDuration as $date => $label) {
            if (Auth::user()->type == 'Owner') {
                $data = Order::select(\DB::raw('count(*) as total'))->where('user_id', $userstore->store_id)->whereDate('created_at', '=', $date)->first();
            } else {
                $data = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

    public function stripe(Request $request)
    {
        $price   = 100;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $payment_setting = Utility::getAdminPaymentSetting();
        if ($price > 0.0) {
            Stripe\Stripe::setApiKey(!empty($payment_setting['stripe_secret']) ? $payment_setting['stripe_secret'] : '');
            $data = Stripe\Charge::create(
                [
                    "amount" => 100 * $price,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => " Test Plan ",
                    "metadata" => ["order_id" => $orderID],
                ]
            );
        }
    }

    public function check()
    {
    }

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();

        $user = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                // 'profile' => 'required',
            ]
        );

        if ($request->hasFile('profile')) {
            $image_size = $request->file('profile')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $filenameWithExt = $request->file('profile')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('profile')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $settings = Utility::getStorageSetting();
                // $settings = Utility::settings();
                $dir        = 'uploads/profile';

                $image_path = $dir . $userDetail['avatar'];
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            } else {
                return redirect()->back()->with('error', 'Plan storage limit is over so please upgrade the plan.');
            }
        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();


        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    public function updatePassword(Request $request)
    {
        if (\Auth::Check()) {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = \Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id            = \Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function changeMode()
    {
        $usr = Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode = 'dark';
        } else {
            $usr->mode = 'light';
        }
        $usr->save();

        return redirect()->back();
    }
}
