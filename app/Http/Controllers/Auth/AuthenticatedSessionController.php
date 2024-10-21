<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Languages;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Product;
use App\Models\User;
use App\Models\Utility;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */


    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;



    public function __construct()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }
        $settings = Utility::settings();
        if($settings['recaptcha_module'] == 'yes')
        {
            config(['captcha.secret'  => $settings['google_recaptcha_secret']]);
            config(['captcha.sitekey' => $settings['google_recaptcha_key']]);
        }
    }

    protected function authenticated(Request $request, $user)
    {
    }


    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $settings = Utility::settings();
        $lang = !empty($settings['default_language']) ? $settings['default_language'] : 'en';

        $validation = [];
        // ReCpatcha
        if(isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
        {
            if($settings['google_recaptcha_version'] == 'v2'){
                $validation['g-recaptcha-response'] = 'required|captcha';
            } elseif ($settings['google_recaptcha_version'] == 'v3'){
                $result = event(new VerifyReCaptchaToken($request));
                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]);
                    $validation['g-recaptcha-response'] = 'required';
                }
            }
        }

        $this->validate($request, $validation);
        $request->authenticate();

        $request->session()->regenerate();
        $user = \Auth::user();

        if (isset($user->is_active) && $user->is_active == 0 || isset($user->is_enable_login) && $user->is_enable_login == 0) {
            auth()->logout();
            return redirect('/login'.'/'.$lang)->with('status', __('Your Account has been Deactivated. Please contact your Site Admin.!'));
        }

        if ($user->type == 'Owner') {
            $store = Store::where('id', $user->current_store)->first();
            if (isset($store->is_store_enabled) && $store->is_store_enabled == 0) {
                auth()->logout();
                return redirect('/login'.'/'.$lang)->with('status', __('Your Store has been Deactivated. Please contact your Site Admin.!'));
            }

            $plan = Plan::find($user->plan);
            if ($plan) {
                $products = Product::where('store_id',$store->id)->get();
                if ($plan->duration != 'Lifetime') {
                    $datetime1 = new \Datetime($user->plan_expire_date);
                    $datetime2 = new \Datetime(date('Y-m-d'));
                    $interval = $datetime2->diff($datetime1);
                    $days = $interval->format('%r%a');
                    if ($days <= 0) {
                        $user->assignPlan(1);

                        $currentPlan =  Plan::find($user->plan);
                        foreach ($products as $key => $product) {
                            $key = $key+1;
                            if ($currentPlan->max_products != -1 && $key > $currentPlan->max_products) {
                                $product['product_display'] = 'off';
                                $product->save();
                            } else {
                                $product['product_display'] = 'on';
                                $product->save();
                            }
                        }

                        return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Plan is expired.'));
                    } else {
                        if($user->trial_expire_date != null)
                        {
                            if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                            {
                                $user->assignPlan(1);

                                return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                            }
                        }
                        foreach ($products as $key => $product) {
                            $key = $key+1;
                            if ($plan->max_products != -1 && $key > $plan->max_products) {
                                $product['product_display'] = 'off';
                                $product->save();
                            } else {
                                $product['product_display'] = 'on';
                                $product->save();
                            }
                        }
                    }
                } else {
                    if($user->trial_expire_date != null)
                        {
                            if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                            {
                                $user->assignPlan(1);

                                return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                            }
                        }
                    foreach ($products as $key => $product) {
                        $key = $key+1;
                        if ($plan->max_products != -1 && $key > $plan->max_products) {
                            $product['product_display'] = 'off';
                            $product->save();
                        } else {
                            $product['product_display'] = 'on';
                            $product->save();
                        }
                    }
                }
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }



    public function showLoginForm($lang = '')
    {
        if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }

        $language_name = Languages::where('code', $lang)->get()->first();

        if (isset($language_name)) {
            \App::setLocale($lang);

            return view('auth.login', compact('lang', 'language_name'));
        } else {
            return redirect()->back();
        }
    }

    public function showLinkRequestForm($lang = '')
    {

        $admin_setting = Utility::settings();
        if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }
        if (empty($admin_setting['mail_password'] && $admin_setting['mail_username'])) {
            return redirect('/login'.'/'.$lang)->with('status', __('SMTP configuration not found. Please contact your site admin.'));
        }

        $language_name = Languages::where('code', $lang)->get()->first();

            \App::setLocale($lang);
            return view('auth.forgot-password', compact('lang', 'language_name'));
        // } else {
        //     return redirect()->back();
        // }
    }
}
