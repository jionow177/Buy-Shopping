<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'ip_address',
        'country_code',
        'city_name',
        'country_name',
        'os_name',
        'browser_name',
        'referrer_host',
        'referrer_path',
        'device_type',
        'browser_language',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'pageview'
    ];
}
