<?php

namespace App\Http\Controllers;

use App\Models\ProductTax;
use Illuminate\Http\Request;
use App\Models\Store;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TaxExport;
use App\Models\Product;

class ProductTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Product Tax')) {
            $user         = \Auth::user()->current_store;
            $product_taxs = ProductTax::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();
            $store = Store::where('id', $user)->first();

            return view('producttax.index', compact('product_taxs', 'store'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function export()
    {
        $name = 'ProductTax' . date('Y-m-d i:h:s');
        $data = Excel::download(new TaxExport(), $name . '.xlsx');

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('Create Product Tax')) {
            return view('producttax.create');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Product Tax')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'tax_name' => 'required|max:120',
                    'rate' => 'required|numeric',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $name                 = $request['tax_name'];
            $producttax           = new ProductTax();
            $producttax->name = $name;
            $producttax->rate     = $request['rate'];
            $producttax['store_id']  = \Auth::user()->current_store;
            $producttax['created_by']  = \Auth::user()->creatorId();
            $producttax->save();

            return redirect()->back()->with('success', __('Product Tax added!'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductTax  $productTax
     * @return \Illuminate\Http\Response
     */
    public function show(ProductTax $productTax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductTax  $productTax
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductTax $productTax)
    {
        if (\Auth::user()->can('Edit Product Tax')) {
            return view('producttax.edit', compact('productTax'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductTax  $productTax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductTax $productTax)
    {
        if (\Auth::user()->can('Edit Product Tax')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'rate' => 'required|numeric',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $productTax['name'] = $request->name;
            $productTax['rate']     = $request->rate;
            $producttax['created_by']  = \Auth::user()->creatorId();
            $productTax->update();

            return redirect()->back()->with(
                'success',
                __('Product Tax updated!')
            );
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductTax  $productTax
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductTax $productTax)
    {
        if (\Auth::user()->can('Delete Product Tax')) {
            $product = Product::where('product_tax', $productTax->id)->get();

            if ($product->count() != 0) {
                return redirect()->back()->with(
                    'error',
                    __('Tax is used in products!')
                );
            } else {
                $productTax->delete();

                return redirect()->back()->with(
                    'success',
                    __('Product Tax Deleted!')
                );
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
