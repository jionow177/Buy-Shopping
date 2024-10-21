<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Store;


class ThemeController extends Controller
{
    public function index(){
        if(\Auth::user()->can('Manage Themes')){
            $settings = Utility::settings();
                $store         = \Auth::user();

            $store_id = $store->current_store;

            $store_settings = Store::where('id',  $store_id)->first();
            return view('themes.theme',compact('store_settings'));
        }
        else{
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }
}
