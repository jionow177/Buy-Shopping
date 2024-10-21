<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expresscheckout extends Model
{
    use HasFactory;
    protected $table = 'express_checkout';
    protected $fillable = [
        'quantity','url','store_id','created_by'
    ];
    public function product(){
        return $this->hasOne(\App\Models\Product::class,'id','product_id');
    }
}
