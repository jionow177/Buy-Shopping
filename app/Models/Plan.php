<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_stores',
        'max_products',
        'max_users',
        'storage_limit',
        'enable_custdomain',
        'enable_custsubdomain',
        'shipping_method',
        'image',
        'description',
        'pwa_store',
        'enable_chatgpt',
        'trial',
        'trial_days',
        'is_active',
    ];

    public static $arrDuration = [
        'Lifetime' => 'Lifetime',
        'Month' => 'Per Month',
        'Year' => 'Per Year',
    ];

    public function status()
    {
        return [
            __('Lifetime'),
            __('Per Month'),
            __('Per Year'),
        ];
    }

    public static function total_plan()
    {
        return Plan::count();
    }

    private static $most_purchese_plan = null;
    private static $return_most_purchese_plan = null;
    
    public static function most_purchese_plan()
    {
        if (is_null(self::$most_purchese_plan)) {
            $free_plan = Plan::where('price', '<=', 0)->first()->id;
            self::$most_purchese_plan = $free_plan;
        }
        if (is_null(self::$return_most_purchese_plan)) {
            self::$return_most_purchese_plan = User:: select('plans.name', 'plans.id', \DB::raw('count(*) as total'))->join('plans', 'plans.id', '=', 'users.plan')->where('type', '=', 'owner')->where('plan', '!=', self::$most_purchese_plan)->orderBy('total', 'Desc')->groupBy('plans.name', 'plans.id')->first();
        }
        return self::$return_most_purchese_plan;
    }
}
