<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductCategorie;
use App\Models\ProductTax;
use App\Models\Store;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection,WithHeadings
{


protected $id;

 function __construct($id) {
        $this->id = $id;
 }


 public function collection()
    {

        $user             = \Auth::user();
        isset($user->current_store) ? $user->current_store  : '';
        $storeid         = Store::where('id', $user->current_store)->first();

        $data           = Product::where('store_id', $storeid->id)->get();


        foreach($data as $k => $product)
        {
        	$store_id=Store::find($product->store_id);
        	$store=isset($store_id)?$store_id->name:'';

        	$product_taxs=ProductTax::find($product->product_tax);
        	$product_tax=isset($product_taxs)?$product_taxs->name:'';

        	$product_categories=ProductCategorie::find($product->product_categorie);
        	$product_categorie=isset($product_categories)?$product_categories->id:'';

        	$created_bys=User::find($product->created_by);
        	$created_by=isset($created_bys)?$created_bys->name:'';

        	 $data[$k]["store_id"]=$store;
        	 $data[$k]["product_tax"]=$product_tax;
        	 $data[$k]["product_categorie"]=$product_categorie;
        	 $data[$k]["created_by"]=$created_by;

        }


        return $data;
    }
    public function headings(): array
    {
        return [
            "Product Id",
            "Store Name",
            "Product Name",
            "Product Categorie",
            "Price",
            "Quantity",
            "SKU",
            "Product Tax",
            "Custom_Field_1",
            "Custom_Value_1",
            "Custom_field_2",
            "Custom_value_2",
            "Custom_field_3",
            "Custom_value_3",
            "Custom_field_4",
            "Custom_value_4",
            "Product Display",
            "Downloadable Prodcut",
            "Enable_Product_Variant",
            "Variants_Json",
            "Is_Cover",
            "Attachment",
            "Is_Active",
            "Description",
            "Detail",
            "Specification",
            "Created_by",
            "Created_at",
            "updated_at",
        ];
    }
}

