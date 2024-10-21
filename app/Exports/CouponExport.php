<?php

namespace App\Exports;

use App\Models\Coupon;
use App\Models\Store;
use App\Models\Product;
use App\Models\Productcoupon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponExport implements FromCollection,WithHeadings
{


    public function collection()

    {
         $data = Coupon::get();

        foreach($data as $k => $coupon)
        {

            $data[$k]["name"]  = $coupon->name;
            $data[$k]["code"]  = $coupon->code;
            $data[$k]["discount"]  = $coupon->discount;
            $data[$k]["limit"]  = $coupon->limit;
            $data[$k]["used"]  = $coupon->used_coupon();


            unset( $data[$k]->id, $data[$k]->description, $data[$k]->is_active, $data[$k]->created_at, $data[$k]->updated_at);
        }

        return $data;
    }


     public function headings(): array
    {
        return [
        "NAME",
        "CODE",
        "DISCOUNT (%)",
        "LIMIT",
        "USED",
        ];
    }
}


