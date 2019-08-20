<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductSale;
use App\Models\SaleOrder;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Payment;
use App\Models\Product;

use Auth;
use PDF;

class ProductSaleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'product_sale_list']);
        $user = Auth::user();
        $customers = Customer::all();

        $mod = new ProductSale();
        $reference_no = $customer_id = $period = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('customer_id') != ""){
            $customer_id = $request->get('customer_id');
            $mod = $mod->where('customer_id', $customer_id);
        }
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
            $mod = $mod->whereBetween('timestamp', [$from, $to]);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $customer_array = Customer::where('company', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $customer_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('customer_id', $customer_array)
                        ->orWhere('timestamp', 'LIKE', "%$keyword%")
                        ->orWhere('grand_total', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('timestamp', $sort_by_date)->paginate($pagesize);
        return view('product_sale.index', compact('data', 'customers', 'customer_id', 'reference_no', 'period', 'keyword', 'sort_by_date'));
    }
    
    public function create(Request $request){
        config(['site.page' => 'add_product_sale']);
        $customers = Customer::all();
        $products = Product::all();
        $next_reference_no = 'FV-'.str_pad(ProductSale::max('id') + 1, 6, "0", STR_PAD_LEFT);
        return view('product_sale.create', compact('customers', 'products', 'next_reference_no'));
    }

    public function save(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'customer'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }

        // dd($data);
        $item = new ProductSale();
        $item->user_id = Auth::user()->id;
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->customer_id = $data['customer'];
        // if($data['credit_days'] != ''){
        //     $item->credit_days = $data['credit_days'];
        //     $item->expiry_date = date('Y-m-d', strtotime("+".$data['credit_days']."days", strtotime($item->timestamp)));
        // }        
        // $item->credit_days = $data['credit_days'];
        $item->status = $data['status'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "product_sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_sale_images/'), $imageName);
            $item->attachment = 'images/uploaded/product_sale_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = -1 * $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();

        if(isset($data['product_id']) && count($data['product_id']) > 0){

            for ($i=0; $i < count($data['product_id']); $i++) { 
                SaleOrder::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'subtotal' => $data['subtotal'][$i],
                    'product_sale_id' => $item->id,
                ]);
            }
        }       

        return redirect(route('product_sale.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'product_sale_list']);
        $sale = ProductSale::find($id);        
        $customers = Customer::all();
        $products = Product::all();

        return view('product_sale.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'customer'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }
        // dd($data);
        $item = ProductSale::find($request->get("id"));
 
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->customer_id = $data['customer'];
        // if($data['credit_days'] != ''){
        //     $item->credit_days = $data['credit_days'];
        //     $item->expiry_date = date('Y-m-d', strtotime("+".$data['credit_days']."days", strtotime($item->timestamp)));
        // }
        $item->status = $data['status'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "product_sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_sale_images/'), $imageName);
            $item->attachment = 'images/uploaded/product_sale_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = -1 * $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();

        $sale_orders = $item->orders->pluck('id')->toArray();
        $diff_orders = array_diff($sale_orders, $data['order_id']);
        foreach ($diff_orders as $key => $value) {
            SaleOrder::find($value)->delete();
        }        
        
        if(isset($data['order_id']) && count($data['order_id']) > 0){
            for ($i=0; $i < count($data['order_id']); $i++) {
                if($data['order_id'][$i] == ''){
                    SaleOrder::create([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'subtotal' => $data['subtotal'][$i],
                        'product_sale_id' => $item->id,
                    ]);
                }else{
                    $order = SaleOrder::find($data['order_id'][$i]);
                    $order->update([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'subtotal' => $data['subtotal'][$i],
                    ]);
                }
            }
        }
        
        return redirect(route('product_sale.index'))->with('success', __('page.updated_successfully'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'product_sale_list']);
        $sale = ProductSale::find($id);

        return view('product_sale.detail', compact('sale'));
    }

    public function report($id){
        $sale = ProductSale::find($id);
        $pdf = PDF::loadView('product_sale.report', compact('sale'));  
        return $pdf->download('product_sale_report_'.$sale->reference_no.'.pdf');
    }
    public function report_view($id){
        $sale = ProductSale::find($id);
        $pdf = PDF::loadView('product_sale.report', compact('sale'));  
        // return $pdf->download('product_sale_report_'.$sale->reference_no.'.pdf');
        return view('product_sale.report', compact('sale'));  
    }

    public function delete($id){
        $item = ProductSale::find($id);
        $item->orders()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
