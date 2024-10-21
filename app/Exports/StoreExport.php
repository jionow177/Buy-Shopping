<?php

namespace App\Exports;

use App\Models\Store;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreExport implements FromCollection,WithHeadings
{


    public function collection()
    {

        $users = User::select(
            [
                'users.*',
                'stores.is_store_enabled as store_display',
            ]
        )->join('stores', 'stores.created_by', '=', 'users.id')->where('users.created_by', \Auth::user()->creatorId())->where('users.type', '=', 'Owner')->get();


        foreach($users as $k => $usr)
        {
            
            $users[$k]['name']      = $usr->name;
            $users[$k]['email']     = $usr->email;
            $users[$k]['stores']    = $usr->stores->count();
            $users[$k]['plan']       = !empty($usr->currentPlan->name)?$usr->currentPlan->name:'-';
            $users[$k]['created_at'] = \App\Models\Utility::dateFormat($usr->created_at);

            // dd( $users[$k]['name'],$users[$k]['stores'],  $users[$k]['email'],  $users[$k]['plan'], $users[$k]['created_at']);

            unset($users[$k]->id, $users[$k]->email_verified_at, $users[$k]->password, $users[$k]->remember_token, $users[$k]->lang, $users[$k]->current_store, $users[$k]->avatar, $users[$k]->type, $users[$k]->requested_plan, $users[$k]->plan_expire_date, $users[$k]->created_by, $users[$k]->mode, $users[$k]->plan_is_active, $users[$k]->updated_at , $users[$k]->store_display);

        }

        return $users;
    }


     public function headings(): array
    {
        return [
        "USER NAME",
        "EMAIL",
        "PLAN",
        "CREATED AT",
        "STORES",
        ];
    }
}


