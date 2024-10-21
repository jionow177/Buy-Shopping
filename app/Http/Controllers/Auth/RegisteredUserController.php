<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\User;
use App\Models\Plan;
use Spatie\Permission\Models\Role;
use App\Models\Store;
use App\Models\UserStore;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
        $settings = Utility::settings();
        if($settings['recaptcha_module'] == 'yes')
        {
            config(['captcha.secret'  => $settings['google_recaptcha_secret']]);
            config(['captcha.sitekey' => $settings['google_recaptcha_key']]);
        }
    }

    // public function create()
    // {
    //     return view('auth.register');
    // }



    public function showRegistrationForm($ref = '',$lang = 'en')
    {
        if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }

        if (Utility::getValByName('signup_button') == 'on') {
            $language_name = Languages::where('code',$lang)->get()->first();
            \App::setLocale($lang);

            if ($ref == '') {
                $ref = 0;
            }
            
            $refCode = User::where('referral_code' , '=', $ref)->first();
            if (!isset($refCode) || $refCode->referral_code != $ref) {
                return redirect()->route('register');
            }

            return view('auth.register', compact('lang','language_name','ref'));
        } else {
            return abort('404', 'Page not found');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validation = [];
        if ($request->has('terms_condition') && $request->terms_condition == 'off') {
            $validation['terms_condition_check'] = 'required';
        }
        $settings = Utility::settings();
        $lang = !empty($settings['default_language']) ? $settings['default_language'] : 'en';

        do {
            $refferal_code = rand(100000 , 999999);
            $checkCode = User::where('type','Owner')->where('referral_code', $refferal_code)->get();
        } while ($checkCode->count());

        if(Utility::getValByName('verification_btn') == 'on'){

            $validation['name']         = 'required|string|max:255';
            $validation['email']        = 'required|string|email|max:255|unique:users';
            $validation['password']     = ['required', 'confirmed', 'min:8', Rules\Password::defaults()];
            $validation['store_name']   = 'required|string|max:255';

            $request->validate($validation);
            $settings = Utility::settings();
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
            $date = null;
            $user = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => Hash::make($request->password),
                'type'                  => 'Owner',
                'lang'                  => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                'avatar'                => 'avatar.png',
                'plan'                  => Plan::first()->id,
                'email_verified_at'     => $date,
                'referral_code'         => $refferal_code,
                'used_referral_code'    => $request->ref_code,
                'created_by'            => 1,
            ]);

            $role_r = Role::findByName('Owner');
            $user->assignRole($role_r);


            $objStore = Store::create(
                [
                    'created_by' => $user->id,
                    'name' =>  $request->store_name,
                    'email' => $request->email,
                    'logo' => !empty($settings['logo']) ? $settings['logo'] : 'logo.png',
                    'invoice_logo' => !empty($settings['logo']) ? $settings['logo'] : 'invoice_logo.png',
                    'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                    'currency' => !empty($settings['currency_symbol']) ? $settings['currency_symbol'] : '$',
                    'currency_code' => !empty($settings['currency']) ? $settings['currency'] : 'USD',

                ]
            );

            $objStore->enable_storelink = 'on';
            $objStore->theme_dir        = 'theme7';
            $objStore->store_theme = 'theme7-v1';
            $objStore->content          = 'Hi,
Welcome to {store_name},
Your order is confirmed & your order no. is {order_no}
Your order detail is:
Name : {customer_name}
Address : {billing_address} , {shipping_address}
~~~~~~~~~~~~~~~~
{item_variable}
~~~~~~~~~~~~~~~~
Qty Total : {qty_total}
Sub Total : {sub_total}
Discount Price : {discount_amount}
Shipping Price : {shipping_amount}
Tax : {item_tax}
Total : {item_total}
~~~~~~~~~~~~~~~~~~
To collect the order you need to show the receipt at the counter.
Thanks {store_name}';
            $objStore->item_variable    = '{sku} : {quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}';
            $objStore->save();

            $user->current_store = $objStore->id;
            $user->save();
            UserStore::create(
                [
                    'user_id' => $user->id,
                    'store_id' => $objStore->id,
                    'permission' => 'Owner',
                ]
            );

            // Auth::login($user);

            // event(new Registered($user));
            try {
                Utility::getSMTPDetails(1);

                event(new Registered($user));

                Auth::login($user);
            } catch (\Exception $e) {

                $user->delete();
                $objStore->delete();

                return redirect('/register/'. $request->ref_code . '/' . $lang)->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }

            return redirect(RouteServiceProvider::HOME);

        } else {
            $validation['name']         = 'required|string|max:255';
            $validation['email']        = 'required|string|email|max:255|unique:users';
            $validation['password']     = ['required', 'confirmed', 'min:8', Rules\Password::defaults()];
            $validation['store_name']   = 'required|string|max:255';

            $request->validate($validation);
        $settings = Utility::settings();
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
        if (Utility::getValByName('verification_btn') == 'off') {
            $date = date("Y-m-d H:i:s");
        }else{
            $date = null;
        }
        $user = User::create([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'type'                  => 'Owner',
            'lang'                  => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
            'avatar'                => 'avatar.png',
            'plan'                  => Plan::first()->id,
            'email_verified_at'     => $date,
            'referral_code'         => $refferal_code,
            'used_referral_code'    => $request->ref_code,
            'created_by'            => 1,
        ]);

        $role_r = Role::findByName('Owner');
        $user->assignRole($role_r);


        $objStore = Store::create(
            [
                'created_by' => $user->id,
                'name' =>  $request->store_name,
                'email' => $request->email,
                'logo' => !empty($settings['logo']) ? $settings['logo'] : 'logo.png',
                'invoice_logo' => !empty($settings['logo']) ? $settings['logo'] : 'invoice_logo.png',
                'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                'currency' => !empty($settings['currency_symbol']) ? $settings['currency_symbol'] : '$',
                'currency_code' => !empty($settings['currency']) ? $settings['currency'] : 'USD',

            ]
        );

        $objStore->enable_storelink = 'on';
        $objStore->theme_dir        = 'theme7';
        $objStore->store_theme = 'theme7-v1';
        $objStore->content          = 'Hi,
Welcome to {store_name},
Your order is confirmed & your order no. is {order_no}
Your order detail is:
Name : {customer_name}
Address : {billing_address} , {shipping_address}
~~~~~~~~~~~~~~~~
{item_variable}
~~~~~~~~~~~~~~~~
Qty Total : {qty_total}
Sub Total : {sub_total}
Discount Price : {discount_amount}
Shipping Price : {shipping_amount}
Tax : {item_tax}
Total : {item_total}
~~~~~~~~~~~~~~~~~~
To collect the order you need to show the receipt at the counter.
Thanks {store_name}';
        $objStore->item_variable    = '{sku} : {quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}';
        $objStore->save();

        $user->current_store = $objStore->id;
        $user->save();
        UserStore::create(
            [
                'user_id' => $user->id,
                'store_id' => $objStore->id,
                'permission' => 'Owner',
            ]
        );

        // Auth::login($user);

        // event(new Registered($user));
        try {
            $dArr = [
                'owner_name' => $user->name,
                'owner_email' => $user->email,
                'owner_password' => $request->password,
            ];

            $resp = Utility::sendEmailTemplate('Owner Created', $user->email, $dArr, $objStore);

            // event(new Registered($user));
            Auth::login($user);
        } catch (\Exception $e) {

            $user->delete();
            $objStore->delete();

            return redirect('/register/'. $request->ref_code . '/' . $lang)->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
        }

        return redirect(RouteServiceProvider::HOME);

        }
    }
}
