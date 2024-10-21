<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'name',
        'price',
        'location_id',
        'store_id',
        'created_by',
    ];

    private static $getResult = null;
    public function locationName()
    {
        if (is_null(self::$getResult)) {
            self::$getResult =  Location::whereIn('id',explode(',',$this->location_id))->get()->pluck('name')->toArray();
        }
        return implode(', ',self::$getResult);
    }
}
