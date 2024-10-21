<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralTransaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'owner_id',
        'plan_id',
        'plan_price',
        'commission',
        'referral_code',
    ];

    public function getPlan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan_id');
    }

    public function getUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner_id');
    }

    // public function getOwner()
    // {
    //     return $this->hasOne('App\Models\User', 'id', 'req_user_id');
    // }
}
