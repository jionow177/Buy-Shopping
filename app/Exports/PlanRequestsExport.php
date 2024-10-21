<?php

namespace App\Exports;

use App\Models\Store;
use App\Models\Product;
use App\Models\ProductTax;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\PlanRequest;
use App\Models\Plan;

class PlanRequestsExport implements FromCollection,WithHeadings
{


    public function collection()

    {
        $data = PlanRequest::select(
            [
                'plan_requests.*',
                'plans.max_products as max_products',
                'plans.max_stores as max_stores',
            ]
        )->join('plans', 'plans.id', '=', 'plan_requests.plan_id')->get();

        foreach($data as $k => $prequest)
        {
            // dd($data);
            $data[$k]["user_id"]  = $prequest->user->name;
            $data[$k]["plan_id"]  = $prequest->plan->name;
            // $data[$k]["max_products"]  = $prequest->plan->max_products;
            // $data[$k]["max_stores"]  = $prequest->plan->max_stores;
            $data[$k]["duration"]  = ($prequest->duration == 'monthly') ? __('One Month') : __('One Year');
            $data[$k]["expiry_date"]  = \App\Models\Utility::getDateFormated($prequest->created_at,true);

            unset($prequest->id,$prequest->created_by,$prequest->created_at,$prequest->updated_at,$prequest->store_id);
        }

        return $data;
    }


     public function headings(): array
    {
        return [
        "USER NAME",
        "PLAN NAME",
        "DURATION",
        "MAX PRODUCT",
        "MAX STORE",
        "EXPIRY DATE",
        ];
    }
}


