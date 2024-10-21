<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomDomainRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'store_id',
        'custom_domain',
        'status',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function store()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    public static $statues = [
        'Pending',
        'Approved',
        'Rejected'
    ];
}
