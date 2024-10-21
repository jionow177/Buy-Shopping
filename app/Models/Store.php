<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected     $fillable     = [
        'name',
        'email',
        'store_theme',
        'domains',
        'enable_storelink',
        'enable_subdomain',
        'subdomain',
        'enable_domain',
        'content',
        'item_variable',
        'about',
        'tagline',
        'slug',
        'lang',
        'storejs',
        'currency',
        'currency_code',
        'currency_symbol_position',
        'currency_symbol_space',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'google_analytic',
        'facebook_pixel',
        'footer_note',
        'enable_shipping',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'logo',
        'invoice_logo',
        'is_stripe_enabled',
        'stripe_key',
        'stripe_secret',
        'is_paypal_enabled',
        'paypal_mode',
        'paypal_client_id',
        'paypal_secret_key',
        'enable_bank',
        'bank_number',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
        'created_by',
        'enable_whatsapp',
        'whatsapp_number',
        'enable_telegram',
        'telegrambot',
        'telegramchatid',
        'custom_field_title_1',
        'custom_field_title_2',
        'custom_field_title_3',
        'custom_field_title_4',
    ];

    public static function create($data)
    {
        $obj          = new Utility();
        $table        = with(new Store)->getTable();
        $data['slug'] = $obj->createSlug($table, $data['name']);
        $store        = static::query()->create($data);

        return $store;
    }

    public function currentLanguage()
    {
        return $this->lang;
    }

    public function store_user()
    {
        return $this->hasOne('App\Models\UserStore', 'store_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\ProductCategorie', 'id', 'id');
    }

    // public static function pwa_store($slug)
    // {
    //     $store = Store::where('slug', $slug)->first();
    //     try {

    //         $pwa_data = \File::get(storage_path('uploads/customer_app/store_' . $store->id . '/manifest.json'));

    //         $pwa_data = json_decode($pwa_data);
    //     } catch (\Throwable $th) {
    //         $pwa_data = [];
    //     }
    //     return $pwa_data;
    // }

    private static $pwa_store = null;
    public static function pwa_store($slug)
    {
        if (is_null(self::$pwa_store)) {
            $store = Store::where('slug', $slug)->first();
            self::$pwa_store = $store;
        }

        if (!self::$pwa_store) {
            return [];
        }

        try {
            $manifestPath = storage_path('uploads/customer_app/store_' . self::$pwa_store->id . '/manifest.json');

            if (\File::exists($manifestPath)) {
                $pwa_data = \File::get($manifestPath);
                $pwa_data = json_decode($pwa_data);
            } else {
                $pwa_data = [];
            }
        } catch (\Throwable $th) {
            $pwa_data = [];
        }

        return $pwa_data;
    }

    public static function storeAddress($add, $county = '')
    {
        if (isset($add) && !empty(($add))) {
            if ($add[-1] != ',' && $county == '') {

                $add = $add . ', ';
            }
            if ($add[-1] != '.' && $county == 'country') {
                $add = $add . '. ';
            }
        }
        return $add;
    }
}
