<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantOption extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'location_id',
        'product_id',
        'name',
        'cost',
        'price',
        'quantity',
        'created_by',
    ];

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }

    public static function variant_name($name = '', $counter = 0, $product_id = 0)
    {
        $retuen_name['has_variant'] = 0;
        $retuen_name['name'] = 'verians['.$counter.'][name][]';
        $retuen_name['has_name'][0] = 'verians['.$counter.'][name]';
        $retuen_name['price'] = 'verians['.$counter.'][price]';
        $retuen_name['qty'] = 'verians['.$counter.'][qty]';
        $retuen_name['price_val'] = 0;
        $retuen_name['qty_val'] = 0;
        $creatorId = Auth::user()->creatorId();

        if(!empty($name)) {
            $ProductVariantOption = ProductVariantOption::where('name', $name)->where('product_id', $product_id)->where('created_by', $creatorId)->first();
            if(!empty($ProductVariantOption)) {
                foreach(explode(' : ', $name) as $key => $values) {
                    $retuen_name['has_name'][$key] = 'verians['.$ProductVariantOption->id.'][variants]['.$key.'][]';
                }
                $retuen_name['price_val'] = $ProductVariantOption->price;
                $retuen_name['qty_val'] = $ProductVariantOption->quantity;
                $retuen_name['price'] = 'verians['.$ProductVariantOption->id.'][price]';
                $retuen_name['qty'] = 'verians['.$ProductVariantOption->id.'][quantity]';
                $retuen_name['has_variant'] = 1;
            }
        }
        return $retuen_name;
    }

    public function GetVariant($productId)
    {
        $retuen_name = [];
        if(!empty($name)) {
            $ProductVariantOption = ProductVariantOption::where('product_id', $productId)->where('created_by', auth()->user()->id)->first();
            if(!empty($ProductVariantOption)) {
                foreach(explode(' : ', $name) as $key => $values) {
                    $retuen_name['has_name'][$key] = 'verians['.$ProductVariantOption->id.'][variants]['.$key.'][]';
                }
                $retuen_name['price_val'] = $ProductVariantOption->price;
                $retuen_name['qty_val'] = $ProductVariantOption->quantity;
                $retuen_name['price'] = 'verians['.$ProductVariantOption->id.'][price]';
                $retuen_name['qty'] = 'verians['.$ProductVariantOption->id.'][quantity]';
                $retuen_name['has_variant'] = 1;
            }
        }
        return $retuen_name;
    }


    public function getproductbyvariantId($id){

        return Product::find($id)->variants_json;
    }
}
