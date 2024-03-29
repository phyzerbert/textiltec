<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supply;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ProduceOrder;
use App\Models\ProductSale;
use App\Models\Supplier;
use App\Models\Customer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        config(['site.page' => 'home']);
        $return['total_suppliers'] = Supplier::count();
        $return['total_customers'] = Customer::count();
        $return['total_supply_count'] = Supply::count();
        $supplies = Supply::all();
        $supply_in_quantity = $supply_out_quantity = 0;
        foreach ($supplies as $supply) {
            $supply_in = $supply->purchase_orders()->sum('quantity');
            $supply_in_quantity += $supply_in;
            $supply_out = $supply->produce_orders()->sum('quantity');
            $supply_out_quantity += $supply_out;
        }
        $return['total_supply_quantity'] = $supply_in_quantity - $supply_out_quantity;

        $return['total_product_count'] = Product::count();
        $products = Product::all();
        $product_in_quantity = $product_out_quantity = 0;
        foreach ($products as $product) {
            $produce_order = $product->produce_order;
            if($produce_order){
                $product_in = $product->produce_order->receptions()->sum('quantity');
            }else {
                $product_in = 0;
            }
            
            $product_in_quantity += $product_in;
            $product_out = $product->sale_orders()->sum('quantity');
            $product_out_quantity += $product_out;
        }
        $return['total_product_quantity'] = $product_in_quantity - $product_out_quantity;

        $period = '';

        if($request->period != ''){
            $period = $request->period;            
        }


        // dump($return);






        return view('dashboard.home', compact('return', 'period'));
    }

    public function set_pagesize(Request $request){
        $pagesize = $request->get('pagesize');
        if($pagesize == '') $pagesize = 100000;
        $request->session()->put('pagesize', $pagesize);
        return back();
    }
}
