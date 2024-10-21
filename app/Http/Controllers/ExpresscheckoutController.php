<?php
namespace App\Http\Controllers;
use App\Models\Expresscheckout;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductVariantOption;
class ExpresscheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
       $product = Product::find($id);
       $product_variant_names = [];
       if ($product->enable_product_variant == 'on') {
            $productVariants = ProductVariantOption::where('product_id', $product->id)->get();
            if (!empty(json_decode($product->variants_json, true))) {
                $product_variant_names = json_decode($product->variants_json);
            }
        }
        return view('product.expresscheckout.create',compact('product','product_variant_names'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = Store::where('id',\Auth::user()->current_store)->first();
        $validator = \Validator::make(
            $request->all(),
            [
                'quantity' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $msg['flag'] = 'error';
            $msg['msg'] = $messages->first();
            return $msg;
        }
        if($request->variant_name){
            $variant_name = implode(' : ',$request->variant_name);
            $product_variant = ProductVariantOption::where('product_id',$request->product_id)->where('name',$variant_name)->first();
            $app_url = trim(env('APP_URL'), '/');
            $url = $app_url . '/store/' . $store->slug .'/' . $request->product_id . '/' . $request->quantity .'/' .$variant_name ;
            if($request->quantity > $product_variant->quantity){
                return redirect()->back()->with('error',__('You Can Add Maximum ' . $product_variant->quantity . ' Quantity'));
            }
        }
        else{
            $product = Product::find($request->product_id);
            $app_url = trim(env('APP_URL'), '/');
            $url = $app_url . '/store/' . $store->slug .'/' . $request->product_id . '/' . $request->quantity;
            if($request->quantity > $product->quantity){
                return redirect()->back()->with('error',__('You Can Add Maximum ' . $product->quantity . ' Quantity'));
            }
        }
        $expresscheckout = new Expresscheckout();
        $expresscheckout->quantity = $request->quantity;
        $expresscheckout->variant_name = isset($variant_name) ? $variant_name : '';
        $expresscheckout->product_id = $request->product_id;
        $expresscheckout->url  = $url;
        $expresscheckout->store_id  = \Auth::user()->current_store;
        $expresscheckout->created_by = \Auth::user()->creatorId();
        $expresscheckout->save();
        return redirect()->back()->with('success',__('Url Generated Successfully!'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $expresscheckout = Expresscheckout::find($id);
       $product = Product::find($expresscheckout->product_id);
       $product_variant_names = [];
       if ($product->enable_product_variant == 'on') {
            $productVariants = ProductVariantOption::where('product_id', $product->id)->get();
            if (!empty(json_decode($product->variants_json, true))) {
                $product_variant_names = json_decode($product->variants_json);
            }
        }
       return view('product.expresscheckout.edit',compact('expresscheckout','product_variant_names','product'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $store = Store::where('id',\Auth::user()->current_store)->first();
        $validator = \Validator::make(
            $request->all(),
            [
                'quantity' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $msg['flag'] = 'error';
            $msg['msg'] = $messages->first();
            return $msg;
        }
        if($request->variant_name){
            $variant_name = implode(' : ',$request->variant_name);
            $product_variant = ProductVariantOption::where('product_id',$request->product_id)->where('name',$variant_name)->first();

            $app_url = trim(env('APP_URL'), '/');
            $url = $app_url . '/store/' . $store->slug .'/' . $request->product_id . '/' . $request->quantity .'/' .$variant_name ;
            if($request->quantity > $product_variant->quantity){
                return redirect()->back()->with('error',__('You Can Add Maximum ' . $product_variant->quantity . ' Quantity'));
            }
        }
        else{
            $product = Product::find($request->product_id);
            $app_url = trim(env('APP_URL'), '/');
            $url = $app_url . '/store/' . $store->slug .'/' . $request->product_id . '/' . $request->quantity;
            if($request->quantity > $product->quantity){
                return redirect()->back()->with('error',__('You Can Add Maximum ' . $product->quantity . ' Quantity'));
            }
        }
        $expresscheckout = Expresscheckout::find($id);
        $expresscheckout->quantity = $request->quantity;
        $expresscheckout->variant_name = isset($variant_name) ? $variant_name : '';
        $expresscheckout->product_id = $request->product_id;
        $expresscheckout->url  = $url;
        $expresscheckout->store_id  = \Auth::user()->current_store;
        $expresscheckout->created_by = \Auth::user()->creatorId();
        $expresscheckout->save();
        return redirect()->back()->with('success',__('Url Generated Successfully!'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expresscheckout::find($id)->delete();
        return redirect()->back()->with(
            'success', __('Product Deleted Successfully!')
        );
    }
}
