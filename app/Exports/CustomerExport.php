<?php

namespace App\Exports;

use App\Models\Store;
use App\Models\Product;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection,WithHeadings
{


    public function collection()

    {
         $user             = \Auth::user();
         $store_id         = Store::where('id', $user->current_store)->first();
         $data = Customer::where('store_id',$store_id->id)->get();
        foreach($data as $k => $customer)
        {
            $data[$k]["name"]  = $customer->name;
            $data[$k]["email"]  = $customer->email;
            $data[$k]["phone_number"]  = $customer->phone_number;

            unset($customer->id,$customer->created_by,$customer->created_at,$customer->updated_at,$customer->store_id,$customer->avatar,$customer->lang);
        }

        return $data;
    }


     public function headings(): array
    {
        return [
        "NAME",
        "EMAIL",
        "PHONE NO",
        ];
    }
}


