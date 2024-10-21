<?php

namespace App\Exports;

use App\Models\Shipping;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategorie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection,WithHeadings
{


    public function collection()

    {
         $user             = \Auth::user();
         $store_id         = Store::where('id', $user->current_store)->first();
         $data = ProductCategorie::where('store_id',$store_id->id)->get();

        foreach($data as $k => $category)
        {


            $data[$k]["name"]  = $category->name;
            $data[$k]["name"]  = $category->name;

            unset($category->created_by,$category->created_at,$category->updated_at,$category->store_id,$category->id);
        }

        return $data;
    }


     public function headings(): array
    {
        return [
        "CATEGORY NAME",
        ];
    }
}


