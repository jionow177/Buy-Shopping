<?php

namespace App\Exports;

use App\Models\Store;
use App\Models\Product;
use App\Models\ProductTax;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaxExport implements FromCollection,WithHeadings
{


    public function collection()

    {
         $user             = \Auth::user();
         $store_id         = Store::where('id', $user->current_store)->first();
         $data = ProductTax::where('store_id',$store_id->id)->get();

        foreach($data as $k => $tax)
        {

            $data[$k]["name"]  = $tax->name;

            unset($tax->id,$tax->created_by,$tax->created_at,$tax->updated_at,$tax->store_id);
        }

        return $data;
    }


     public function headings(): array
    {
        return [
        "TAX NAME",
        "RATE %",
        ];
    }
}


