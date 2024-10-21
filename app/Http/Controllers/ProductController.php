<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\ProductCategorie;
use App\Models\ProductVariantOption;
use App\Models\ProductTax;
use App\Models\Store;
use App\Models\User;
use App\Exports\ProductsExport;
use App\Imports\ProductImport;
use App\Models\Expresscheckout;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Utility;

class ProductController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            \App::setLocale(\Auth::user()->lang);
        }
    }

    public function export($id)
    {
        $name = 'products_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductsExport($id), $name . '.xlsx');

        return $data;
    }


    public function index()
    {
        if (\Auth::user()->can('Manage Products')) {
            $user             = \Auth::user();
            $store_id         = Store::where('id', $user->current_store)->first();
            $plan             = Plan::find($user->plan);
            // $products         = Product::where('store_id', $store_id->id)->orderBy('id', 'DESC')->get();
            $products = Product::where('store_id', $store_id->id)->get();
            foreach ($products as $key => $product) {
                $key = $key+1;
                if ($plan->max_products != -1 && $key > $plan->max_products) {
                    $product['product_display'] = 'off';
                    $product->save();
                } else {
                    $product['product_display'] = 'on';
                    $product->save();
                }
            }
            $productcategorie = ProductCategorie::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('product.index', compact('products', 'productcategorie', "store_id"));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('Create Products')) {
            $user              = \Auth::user();
            $store_id          = Store::where('id', $user->current_store)->first();
            $product_categorie = ProductCategorie::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $product_tax       = ProductTax::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('product.create', compact('product_categorie', 'product_tax'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Products')) {
            $user     = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ]
            );
            if ($request->enable_product_variant == '') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'price' => 'required',
                        'quantity' => 'required',
                    ]
                );
            }
            if ($request->enable_product_variant == 'on') {
                if (!empty($request->verians)) {
                    foreach ($request->verians as $k => $items) {
                        foreach ($items as $item_k => $item) {
                            if (empty($item) && $item < 0) {
                                $msg['flag'] = 'error';
                                $msg['msg']  = __('Please Fill The Form');

                                return $msg;
                            }
                        }
                    }
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg']  = __('Please Add Variants');

                    return $msg;
                }
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                $msg['flag'] = 'error';
                $msg['msg']  = $messages->first();

                return $msg;
            }

            $file_name = [];
            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                foreach ($request->multiple_files as $key_files => $file) {
                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $settings = Utility::getStorageSetting();
                        // $settings = Utility::settings();

                        $dir        = 'uploads/product_image/';

                        $path = Utility::keyWiseUpload_file($request, 'multiple_files', $fileNameToStore, $dir, $key_files, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[]     = $fileNameToStore;
                            //  $file_name[]     = $fileNameToStore;
                        }
                    }
                }
            }

            if (isset($request->is_cover_image)) {
                $image_size = $request->file('is_cover_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $filenameWithExt  = $request->file('is_cover_image')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('is_cover_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    if ($result == 1) {
                        $settings = Utility::getStorageSetting();
                        // $settings = Utility::settings();
                        if ($settings['storage_setting'] == 'local') {
                            $dir        = 'uploads/is_cover_image/';
                        } else {
                            $dir        = 'uploads/is_cover_image/';
                        }
                        $path = Utility::upload_file($request, 'is_cover_image', $fileNameToStores, $dir, []);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                    }
                }
            }
            if (!empty($request->downloadable_prodcut)) {
                $image_size = $request->file('downloadable_prodcut')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $filenameWithExt   = $request->file('downloadable_prodcut')->getClientOriginalName();
                    $filename          = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension         = $request->file('downloadable_prodcut')->getClientOriginalExtension();
                    $filedownloadable1 = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    // $settings = Utility::settings();

                    if ($settings['storage_setting'] == 'local') {
                        $dir        = 'uploads/downloadable_prodcut/';
                    } else {
                        $dir        = 'uploads/downloadable_prodcut/';
                    }

                    $filedownloadable = str_replace(' ', '_', $filedownloadable1);
                    $path = Utility::upload_file($request, 'downloadable_prodcut', $filedownloadable, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            if (!empty($request->product_tax)) {
                if (count($request->product_tax) > 1 && in_array(0, $request->product_tax)) {
                    $msg['flag'] = 'error';
                    $msg['msg']  = __('Please select valid tax');

                    return $msg;
                }
            }

            $store = \Auth::user()->current_store;
            $creator       = User::find($user->creatorId());
            // $total_product = $user->countProducts();
            $total_product = $user->countStoreProducts($user->current_store);

            $plan          = Plan::find($creator->plan);

            if ($total_product < $plan->max_products || $plan->max_products == -1) {
                $product             = new Product();
                $product['store_id'] = $store_id->id;
                $product['name']     = $request->name;
                if (!empty($request->product_categorie)) {
                    $product['product_categorie'] = implode(',', $request->product_categorie);
                } else {
                    $product['product_categorie'] = $request->product_categorie;
                }
                if (!empty($request->price)) {
                    $product['price'] = !empty($request->price) ? $request->price : '0';
                }
                if (!empty($request->quantity)) {
                    $product['quantity'] = !empty($request->quantity) ? $request->quantity : '0';
                }
                $product['SKU'] = $request->SKU;
                if (!empty($request->product_tax)) {
                    $product['product_tax'] = implode(',', $request->product_tax);
                } else {
                    $product['product_tax'] = $request->product_tax;
                }

                $product['custom_field_1'] = $request->custom_field_1;
                $product['custom_value_1'] = $request->custom_value_1;
                $product['custom_field_2'] = $request->custom_field_2;
                $product['custom_value_2'] = $request->custom_value_2;
                $product['custom_field_3'] = $request->custom_field_3;
                $product['custom_value_3'] = $request->custom_value_3;
                $product['custom_field_4'] = $request->custom_field_4;
                $product['custom_value_4'] = $request->custom_value_4;

                $product['product_display']        = isset($request->product_display) ? 'on' : 'off';
                $product['enable_product_variant'] = isset($request->enable_product_variant) ? 'on' : 'off';
                $product['variants_json']          = $request->hiddenVariantOptions;
                $product['is_cover']               = isset($request->is_cover_image) ? $fileNameToStores : '';
                $product['downloadable_prodcut']   = isset($request->downloadable_prodcut) ? $filedownloadable : '';
                $product['description']            = $request->description;
                $product['created_by']             = \Auth::user()->creatorId();

                $product->save();

                if (!empty($file_name)) {
                    foreach ($file_name as $file) {
                        $objStore = Product_images::create(
                            [
                                'product_id' => $product->id,
                                'product_images' => $file,
                            ]
                        );
                    }
                }

                if ($request->enable_product_variant == 'on') {
                    $product->variants_json = json_decode($product->variants_json, true);
                    $variant_options = array_column($product->variants_json, 'variant_options');

                    $possibilities = Product::possibleVariants($variant_options);

                    foreach ($possibilities as $key => $possibility) {
                        $VariantOption             = new ProductVariantOption();
                        $VariantOption->name       = $possibility;
                        $VariantOption->product_id = $product->id;
                        $VariantOption->price      = $request->verians[$key]['price'];
                        if ($request->verians[$key]['qty'] == NULL) {
                            $VariantOption->quantity   = 0;
                        } else {
                            $VariantOption->quantity   = $request->verians[$key]['qty'];
                        }
                        $VariantOption->created_by = Auth::user()->creatorId();
                        $VariantOption->save();
                    }
                }


                if (!empty($product)) {
                    $module = 'New Product';
                    $webhook =  Utility::webhook($module, $store_id);
                    if (isset($webhook) && !empty($webhook)) {
                        $parameter = json_encode($product);
                        // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                        $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                        if ($status != true) {
                            $msgs  = 'Webhook call failed.';
                        }
                    }
                    $msg['flag'] = 'success';
                    $msg['msg']  = __('Product Successfully Created') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : '');
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg']  = __('Product Created Failed');
                }
                return $msg;
            } else {
                $msg['flag'] = 'error';
                $msg['msg']  = __('Your product limit is over Please upgrade plan');

                return $msg;
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (\Auth::user()->can('Show Products')) {
            $user  = \Auth::user();
            $store = Store::where('id', $user->current_store)->first();

            if ($product->store_id == $user->current_store) {
                $product_image = Product_images::where('product_id', $product->id)->get();

                $product_tax = ProductTax::where('store_id', $store->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                $variant_name          = json_decode($product->variants_json);
                $product_variant_names = $variant_name;

                $expresscheckout = Expresscheckout::where('store_id', $store->id)->where('product_id', $product->id)->get();

                return view('product.view', compact('product', 'product_image', 'product_tax', 'store', 'product_variant_names', 'expresscheckout'));
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (\Auth::user()->can('Edit Products')) {
            $user              = \Auth::user();
            $store_id          = Store::where('id', $user->current_store)->first();
            if ($product->store_id == $user->current_store) {
                $product_categorie = ProductCategorie::where('store_id', $store_id->id)->get()->pluck('name', 'id');
                $product_image     = Product_images::where('product_id', $product->id)->get();
                $product_tax       = ProductTax::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                $productVariantArrays  = [];
                $product_variant_names = [];
                $variant_options       = [];
                // if ($product->enable_product_variant == 'on') {
                    $productVariants = ProductVariantOption::where('product_id', $product->id)->get();

                    if (!empty(json_decode($product->variants_json, true))) { {
                            $variant_options       = array_column(json_decode($product->variants_json), 'variant_name');
                            $product_variant_names = $variant_options;
                        }

                        foreach ($productVariants as $key => $productVariant) {
                            $productVariantArrays[$key]['product_variants'] = $productVariant->toArray();
                        }
                    }
                // }
                return view('product.edit', compact('product', 'product_categorie', 'product_image', 'product_tax', 'productVariantArrays', 'product_variant_names', 'variant_options'));
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        //
    }

    public function productUpdate(Request $request, $product_id)
    {
        if (\Auth::user()->can('Edit Products')) {
            $product = Product::find($product_id);

            $user     = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ]
            );

            if ($request->enable_product_variant == '') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'price' => 'required',
                        'quantity' => 'required|min:1',
                        //    'is_cover_image' => 'required',
                        //    'downloadable_prodcut' => 'required',
                    ]
                );
            }

            if ($request->enable_product_variant == 'on') {
                if (!empty($request->verians || $request->variants)) {
                    if (!empty($request->verians)) {
                        foreach ($request->verians as $k => $items) {
                            foreach ($items as $item_k => $item) {
                                if (!isset($item)) {
                                    $msg['flag'] = 'error';
                                    $msg['msg'] = __('Please Fill The Form');

                                    return $msg;
                                }
                            }
                        }
                    } else {
                        foreach ($request->variants as $k => $items) {
                            foreach ($items as $item_k => $item) {
                                if (!isset($item)) {
                                    $msg['flag'] = 'error';
                                    $msg['msg'] = __('Please Fill The Form');

                                    return $msg;
                                }
                            }
                        }
                    }
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg'] = __('Please Add Variants');

                    return $msg;
                }
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                $msg['flag'] = 'error';
                $msg['msg']  = $messages->first();

                return $msg;
            }

            $file_name = [];

            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {

                foreach ($request->multiple_files as $key => $file) {

                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                    if ($result == 1) {
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        // $file_name[] = $fileNameToStore;
                        $settings = Utility::getStorageSetting();
                        // $settings = Utility::settings();

                        $dir = 'uploads/product_image/';
                        $path = Utility::keyWiseUpload_file($request, 'multiple_files', $fileNameToStore, $dir, $key, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[] = $fileNameToStore;
                        }
                    }
                    // $dir             = storage_path('uploads/product_image/');
                    // if(!file_exists($dir))
                    // {
                    //     mkdir($dir, 0777, true);
                    // }
                    // $path = $file->storeAs('uploads/product_image/', $fileNameToStore);
                }
            }

            if (!empty($request->is_cover_image)) {
                $fileName = $product->is_cover !== 'default.jpg' ? $product->is_cover : '';
                $filePath = 'uploads/is_cover_image/' . $fileName;

                $image_size = $request->file('is_cover_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if ($result == 1) {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
                    $filenameWithExt  = $request->file('is_cover_image')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('is_cover_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    // $settings = Utility::settings();

                    if ($settings['storage_setting'] == 'local') {
                        $dir        = 'uploads/is_cover_image/';
                    } else {
                        $dir        = 'uploads/is_cover_image/';
                    }
                    $path = Utility::upload_file($request, 'is_cover_image', $fileNameToStores, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                }
            }
            if (!empty($request->downloadable_prodcut)) {

                $fileName = $product->downloadable_prodcut !== 'default.jpg' ? $product->downloadable_prodcut : '';
                $filePath = 'uploads/downloadable_prodcut/' . $fileName;

                $image_size = $request->file('downloadable_prodcut')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if ($result == 1) {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
                    $filenameWithExt   = $request->file('downloadable_prodcut')->getClientOriginalName();
                    $filename          = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension         = $request->file('downloadable_prodcut')->getClientOriginalExtension();
                    $filedownloadable1 = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    // $settings = Utility::settings();

                    if ($settings['storage_setting'] == 'local') {
                        $dir        = 'uploads/downloadable_prodcut/';
                    } else {
                        $dir        = 'uploads/downloadable_prodcut/';
                    }

                    $filedownloadable = str_replace(' ', '_', $filedownloadable1);
                    $path = Utility::upload_file($request, 'downloadable_prodcut', $filedownloadable, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            if (!empty($request->product_tax)) {
                if (count($request->product_tax) > 1 && in_array(0, $request->product_tax)) {
                    return redirect()->back()->with('error', __('Please select valid tax'));
                }
            }

            $product['store_id'] = $store_id->id;
            $product['name']     = $request->name;
            if (!empty($request->product_categorie)) {
                $product['product_categorie'] = implode(',', $request->product_categorie);
            } else {
                $product['product_categorie'] = $request->product_categorie;
            }
            if (!empty($request->price)) {
                $product['price'] = !empty($request->price) ? $request->price : '0';
            }
            if (!empty($request->quantity)) {
                $product['quantity'] = !empty($request->quantity) ? $request->quantity : '0';
            }
            $product['SKU'] = $request->SKU;
            if (!empty($request->product_tax)) {
                $product['product_tax'] = implode(',', $request->product_tax);
            } else {
                $product['product_tax'] = $request->product_tax;
            }
            $product['custom_field_1'] = $request->custom_field_1;
            $product['custom_value_1'] = $request->custom_value_1;
            $product['custom_field_2'] = $request->custom_field_2;
            $product['custom_value_2'] = $request->custom_value_2;
            $product['custom_field_3'] = $request->custom_field_3;
            $product['custom_value_3'] = $request->custom_value_3;
            $product['custom_field_4'] = $request->custom_field_4;
            $product['custom_value_4'] = $request->custom_value_4;
            $product['downloadable_prodcut']   = !empty($request->downloadable_prodcut) ? $filedownloadable : '';
            $product['product_display']        = isset($request->product_display) ? 'on' : 'off';

            if ($request->enable_product_variant == 'on') {
                $product['enable_product_variant'] = 'on';
                // $product['variants_json'] = $product->variants_json = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;
                $product['variants_json'] = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;

                if (!empty($request->verians) && count($request->verians) > 0) {

                    foreach ($request->verians as $key => $possibility) {
                        $possibilities = ProductVariantOption::find($key);
                        if (is_null($possibilities)) {
                            $VariantOptionNew = new ProductVariantOption();
                            $VariantOptionNew->name = $possibility['name'];
                            $VariantOptionNew->product_id = $product->id;
                            $VariantOptionNew->price = $possibility['price'];
                            $VariantOptionNew->quantity = $possibility['quantity'] ?? $possibility['qty'];
                            $VariantOptionNew->created_by = Auth::user()->creatorId();
                            $VariantOptionNew->save();
                        } else {


                            $possibilities->price = $possibility['price'];
                            $possibilities->quantity = $possibility['quantity'] ?? $possibility['qty'];

                            $possibilities->save();
                        }
                    }
                } else if (!empty($request->variants) && count($request->variants) > 0) {

                    foreach ($request->variants as $key => $possibility) {
                        $possibilities = ProductVariantOption::find($key);
                        $possibilities->price = $possibility['price'];
                        $possibilities->quantity = $possibility['quantity'] ?? $possibility['qty'];

                        $possibilities->save();
                    }
                }
            } else {
                $product['enable_product_variant'] = 'off';
            }


            if (!empty($request->is_cover_image)) {
                $product['is_cover'] = $fileNameToStores;
            }
            $product['description'] = $request->description;
            $product['created_by']  = \Auth::user()->creatorId();
            foreach ($file_name as $file) {
                $objStore = Product_images::create(
                    [
                        'product_id' => $product->id,
                        'product_images' => $file,

                    ]
                );
            }

            $product->save();
            if ($product->enable_product_variant == 'on') {
                if (!empty($request->variants)) {
                    foreach ($request->variants as $key => $variant) {
                        $newVal = '';

                        foreach (array_values($variant['variants']) as $k => $v) {
                            if (!empty($newVal)) {
                                $newVal .= ' : ' . $v[0];
                            } else {
                                $newVal .= $v[0];
                            }
                        }
                        $VariantOption = ProductVariantOption::find($key);
                        $VariantOption->name = $newVal;
                        $VariantOption->price = $variant['price'];
                        $VariantOption->quantity = $variant['quantity'] ?? $variant['qty'];
                        $VariantOption->save();
                    }
                }
            }

            if (!empty($product)) {
                $msg['flag'] = 'success';
                $msg['msg']  = __('Product Successfully Updated');
            } else {
                $msg['flag'] = 'error';
                $msg['msg']  = __('Product Updated Failed');
            }

            return $msg;
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */

    public function destroy(Product $product)
    {
        if (\Auth::user()->can('Delete Products')) {
            $Product_images = Product_images::where('product_id', $product->id)->get();
            $pro_img = new ProductController();

            $dir = storage_path('uploads/is_cover_image/');
            if (isset($product->is_cover)) {
                $fileName = $product->is_cover != 'default.jpg' ? $product->is_cover : '';
                $filePath = 'uploads/is_cover_image/' . $fileName;

                Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
            }
            if (isset($product->downloadable_prodcut)) {
                $fileName = $product->downloadable_prodcut !== 'default.jpg' ? $product->downloadable_prodcut : '';
                $filePath = 'uploads/downloadable_prodcut/' . $fileName;
                Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
            }
            foreach ($Product_images as $pro) {
                if (isset($pro->product_images)) {
                    $fileName = $pro->product_images != 'default.jpg' ? $pro->product_images : '';
                    $filePath = 'uploads/product_image/' . $fileName;
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
                }
            }
            foreach ($Product_images as $pro) {
                $pro_img->fileDelete($pro->id);
            }
            ProductVariantOption::where('product_id', $product->id)->forceDelete();
            $product->delete();

            return redirect()->back()->with('success', __('Product successfully deleted.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function grid()
    {
        $user     = \Auth::user();
        $plan             = Plan::find($user->plan);
        $store_id = Store::where('id', $user->current_store)->first();
        // $products = Product::where('store_id', $store_id->id)->orderBy('id', 'DESC')->get();
        $products = Product::where('store_id', $store_id->id)->get();
        foreach ($products as $key => $product) {
            $key = $key+1;
            if ($plan->max_products != -1 && $key > $plan->max_products) {
                $product['product_display'] = 'off';
                $product->save();
            } else {
                $product['product_display'] = 'on';
                $product->save();
            }
        }

        return view('product.grid', compact('products', "store_id"));
    }

    public function fileDelete($id)
    {
        $product_img_id = Product_images::find($id);

        $dir = storage_path('uploads/product_image/');
        if (!empty($product_img_id->product_images)) {
            if (!file_exists($dir . $product_img_id->product_images)) {
                Product_images::where('id', $id)->delete();

                return response()->json(
                    [
                        'error' => 'error',
                        'msg' => __('File not exists in folder!'),
                        'id' => $id,
                    ]
                );
            } else {
                $fileName = $product_img_id->product_images != 'default.jpg' ? $product_img_id->product_images : '';
                $filePath = 'uploads/product_image/' . $fileName;

                Utility::changeStorageLimit(\Auth::user()->creatorId(), $filePath);
                Product_images::where('id', '=', $id)->delete();

                return response()->json(
                    [
                        'msg' => __('Record deleted successfully!'),
                        'id' => $id,
                        'success' => 'success'
                    ]
                );
            }
        }

        return response()->json(
            [
                'success' => 'success',
                'msg' => __('Record deleted successfully!'),
                'id' => $id,
            ]
        );
    }


    public function productVariantsCreate(Request $request)
    {

        return view('product.variants.create')->render();
    }

    public function productVariantsEdit(Request $request, $product_id)
    {
        $product = Product::getProductById($product_id);
        $productVariantOption = json_decode($product->variants_json, true);
        if (empty($productVariantOption)) {
            return view('product.variants.create')->render();
        } else {
            return view('product.variants.edit', compact('product', 'productVariantOption', 'product_id'))->render();
        }
    }

    public function getProductVariantsPossibilities(Request $request, $product_id = 0)
    {
        $variant_edit = $request->variant_edit;
        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $variant_option123 = json_decode($request->hiddenVariantOptions, true);
            foreach ($variant_option123 as $key => $value) {
                $new_key = array_search($value['variant_name'], array_column($request->variant_edt, 'variant_name'));
                if (!empty($request->variant_edt[$new_key]['variant_options'])) {
                    $new_val = explode('|', $request->variant_edt[$new_key]['variant_options']);
                    $variant_option123[$key]['variant_options'] = array_merge($variant_option123[$key]['variant_options'], $new_val);
                }
            }
            $request->hiddenVariantOptions = json_encode($variant_option123);
        }


        $variant_name = $request->variant_name;
        $variant_options = $request->variant_options;
        $hiddenVariantOptions = $request->hiddenVariantOptions;


        $hiddenVariantOptions = json_decode($hiddenVariantOptions, true);
        $variants = [
            [
                'variant_name' => $variant_name,
                'variant_options' => explode('|', $variant_options),
            ],
        ];

        if (empty($variant_edit) && $variant_edit != 'edit') {
            $hiddenVariantOptions = array_merge($hiddenVariantOptions, $variants);
        }
        $hiddenVariantOptions = array_map("unserialize", array_unique(array_map("serialize", $hiddenVariantOptions)));

        $optionArray = $variantArray = [];

        foreach ($hiddenVariantOptions as $variant) {
            $variantArray[] = $variant['variant_name'];
            $optionArray[] = $variant['variant_options'];
        }

        $possibilities = Product::possibleVariants($optionArray);

        $variantArray = array_unique($variantArray);
        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $varitantHTML = view('product.variants.edit_list', compact('possibilities', 'variantArray', 'product_id'))->render();
        } else {
            $varitantHTML = view('product.variants.list', compact('possibilities', 'variantArray'))->render();
        }

        $result = [
            'status' => false,
            'hiddenVariantOptions' => json_encode($hiddenVariantOptions),
            'varitantHTML' => $varitantHTML,
        ];
        return response()->json($result);
    }


    public function getProductsVariantQuantity(Request $request)
    {
        $status       = false;
        $quantity     = $variant_id = 0;
        $quantityHTML = '<strong>' . __('Please select variants to get available quantity.') . '</strong>';
        $priceHTML    = '';

        $product = Product::find($request->product_id);
        $price   = \App\Models\Utility::priceFormat(0);
        $status  = false;

        if ($product && $request->variants != '') {
            $variant = ProductVariantOption::where('product_id', $product['id'])->where('name', $request->variants)->first();
            if ($variant) {
                $status     = true;
                $quantity   = $variant->quantity - (isset($cart[$variant->id]['quantity']) ? $cart[$variant->id]['quantity'] : 0);
                $price      = \App\Models\Utility::priceFormat($variant->price);
                $variant_id = $variant->id;
            }
        }

        return response()->json(
            [
                'status' => $status,
                'price' => $price,
                'quantity' => $quantity,
                'variant_id' => $variant_id,
            ]
        );
    }

    public function VariantDelete(Request $request, $id, $product_id)
    {
        if (\Auth::user()->can('Delete Variants')) {
            ProductVariantOption::find($id)->delete();

            if (!ProductVariantOption::where('product_id', $product_id)->exists()) {
                $projsn = Product::find($product_id);
                $projsn->variants_json = '{}';
                $projsn->save();
            }
            return redirect()->back()->with('success', __('Variant successfully deleted.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function fileImportExport()
    {
        if(\Auth::user()->can('Create Products')){
            return view('product.import');
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function fileImport(Request $request)
    {
        if(\Auth::user()->can('Create Products')){
            $rules = [
                'file' => 'required|mimes:csv,txt,xlsx',
            ];
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $products = (new ProductImport())->toArray(request()->file('file'))[0];

            $totalproduct = count($products) - 1;

            $errorArray = [];
            for ($i = 1; $i <= count($products) - 1; $i++) {
                $product = $products[$i];
                $productBySku = Product::where('SKU', $product[2])->first();

                if (!empty($productByname)) {
                    $productData = $productBySku;
                } else {
                    $productData = new Product();
                }
                // $productData->name = $product[2];
                // $productData->product_categorie = $product[3];
                // $productData->price = $product[4];
                // $productData->quantity = $product[5];
                // $productData->product_display = $product[16];
                $productData->name = $product[0];
                $productData->description = $product[1];
                $productData->SKU = $product[2];
                $productData->price = $product[3];
                $productData->quantity = $product[4];
                $productData->store_id = $store_id->id;
                $productData->created_by = \Auth::user()->creatorId();

                if (empty($productData)) {
                    $errorArray[] = $productData;
                } else {
                    $productData->save();
                }
            }

            $errorRecord = [];
            if (empty($errorArray)) {
                $data['status'] = 'success';
                $data['msg'] = __('Record successfully imported');
            } else {
                $data['status'] = 'error';
                $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalproduct . ' ' . 'record');

                foreach ($errorArray as $errorData) {

                    $errorRecord[] = implode(',', $errorData);
                }

                \Session::put('errorArray', $errorRecord);
            }

            return redirect()->back()->with($data['status'], $data['msg']);
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
