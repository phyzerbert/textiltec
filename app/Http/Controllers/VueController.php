<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supply;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Order;
use App\Models\ProduceOrder;
use App\Models\ProductSale;

class VueController extends Controller
{
    
    public function get_supplies() {
        $supplies = Supply::all();

        return response()->json($supplies);
    }    
    
    public function get_products() {
        $products = Product::all();

        return response()->json($products);
    }

    public function get_supply(Request $request) {
        $id = $request->get('id');
        $supply = Supply::find($id);

        return response()->json($supply);
    }

    public function get_product(Request $request) {
        $id = $request->get('id');
        $product = Product::find($id);

        return response()->json($product);
    }

    public function get_orders(Request $request) {
        $id = $request->get('id');
        $item = Purchase::find($id);       
        $orders = $item->orders;
        return response()->json($orders);
    }

    public function get_data(Request $request){
        $id = $request->get('id');
        $item = Purchase::find($id)->load('orders');
        return response()->json($item);
    }

    public function get_product_sale_data(Request $request){
        $id = $request->get('id');
        $item = ProductSale::find($id)->load('orders');
        return response()->json($item);
    }

    public function produce_get_data(Request $request){
        $id = $request->get('id');
        $item = ProduceOrder::find($id)->load('supplies');
        return response()->json($item);
    }

    public function get_first_supply(Request $request){
        $item = Supply::first();
        return response()->json($item);
    }

    public function get_first_product(Request $request){
        $item = Product::first();
        return response()->json($item);
    }

    public function get_autocomplete_supplies(Request $request){
        $keyword = $request->get('keyword');
        $data = Supply::where('name', 'LIKE', "%$keyword%")->orWhere('code', 'LIKE', "%$keyword%")->get();
        return response()->json($data);
    }

    public function get_autocomplete_products(Request $request){
        $keyword = $request->get('keyword');
        $data = Product::where('name', 'LIKE', "%$keyword%")->orWhere('code', 'LIKE', "%$keyword%")->get();
        return response()->json($data);
    }
    
    public function auth_check(Request $request) {
        $auth_id = $request->get('id');
        if($auth_id == auth()->id()) {
            return response()->json('success');
        } else {
            return response()->json('fail');
        }
    }
    
}
