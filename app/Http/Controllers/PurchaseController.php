<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\PurchaseOrder;

use Auth;

class PurchaseController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'purchase_list']);
        $user = Auth::user();
        $suppliers = Supplier::all();

        $mod = new Purchase();
        $reference_no = $supplier_id = $period = $expiry_period = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('supplier_id') != ""){
            $supplier_id = $request->get('supplier_id');
            $mod = $mod->where('supplier_id', $supplier_id);
        }
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
            $mod = $mod->whereBetween('timestamp', [$from, $to]);
        }
        if ($request->get('expiry_period') != ""){   
            $expiry_period = $request->get('expiry_period');
            $from = substr($expiry_period, 0, 10);
            $to = substr($expiry_period, 14, 10);
            $mod = $mod->whereBetween('expiry_date', [$from, $to]);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $supplier_array = Supplier::where('company', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $supplier_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('supplier_id', $supplier_array)
                        ->orWhere('timestamp', 'LIKE', "%$keyword%")
                        ->orWhere('grand_total', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('timestamp', $sort_by_date)->paginate($pagesize);
        return view('purchase.index', compact('data', 'suppliers', 'supplier_id', 'reference_no', 'period', 'expiry_period', 'keyword', 'sort_by_date'));
    }
    
    public function create(Request $request){
        config(['site.page' => 'add_purchase']);
        $suppliers = Supplier::all();
        $supplies = Supply::all();
        return view('purchase.create', compact('suppliers', 'stores', 'products'));
    }

    public function save(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'supplier'=>'required',
            'credit_days' => 'required',
        ]);

        $data = $request->all();
        if(!isset($data['supply_id']) ||  count($data['supply_id']) == 0 || in_array(null, $data['supply_id'])){
            return back()->withErrors(['supply' => 'Please select a prouct.']);
        }

        // dd($data);
        $item = new Purchase();
        $item->user_id = Auth::user()->id;  
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->supplier_id = $data['supplier'];
        if($data['credit_days'] != ''){
            $item->credit_days = $data['credit_days'];
            $item->expiry_date = date('Y-m-d', strtotime("+".$data['credit_days']."days", strtotime($item->timestamp)));
        }        
        $item->credit_days = $data['credit_days'];
        $item->status = $data['status'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "purchase_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/purchase_images/'), $imageName);
            $item->attachment = 'images/uploaded/purchase_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = -1 * $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();

        if(isset($data['supply_id']) && count($data['supply_id']) > 0){

            for ($i=0; $i < count($data['supply_id']); $i++) { 
                PurchaseOrder::create([
                    'supply_id' => $data['supply_id'][$i],
                    'cost' => $data['cost'][$i],
                    'quantity' => $data['quantity'][$i],
                    'expiry_date' => $data['expiry_date'][$i],
                    'subtotal' => $data['subtotal'][$i],
                    'purchase_id' => $item->id,
                ]);
            }
        }       

        return redirect(route('purchase.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'purchase_list']);
        $purchase = Purchase::find($id);        
        $suppliers = Supplier::all();
        $supplies = Supply::all();

        return view('purchase.edit', compact('purchase', 'suppliers', 'supplies'));
    }

    public function update(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'supplier'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['supply_id']) ||  count($data['supply_id']) == 0 || in_array(null, $data['supply_id'])){
            return back()->withErrors(['supply' => 'Please select a supply.']);
        }
        // dd($data);
        $item = Purchase::find($request->get("id"));
 
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->supplier_id = $data['supplier'];
        if($data['credit_days'] != ''){
            $item->credit_days = $data['credit_days'];
            $item->expiry_date = date('Y-m-d', strtotime("+".$data['credit_days']."days", strtotime($item->timestamp)));
        }
        $item->status = $data['status'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "purchase_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/purchase_images/'), $imageName);
            $item->attachment = 'images/uploaded/purchase_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = -1 * $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();
        $purchase_orders = $item->orders->pluck('id')->toArray();
        $diff_orders = array_diff($purchase_orders, $data['order_id']);
        foreach ($diff_orders as $key => $value) {
            PurchaseOrder::find($value)->delete();
        }
        
        if(isset($data['order_id']) && count($data['order_id']) > 0){
            for ($i=0; $i < count($data['order_id']); $i++) {
                if($data['order_id'][$i] == ''){
                    PurchaseOrder::create([
                        'supply_id' => $data['supply_id'][$i],
                        'cost' => $data['cost'][$i],
                        'quantity' => $data['quantity'][$i],
                        'expiry_date' => $data['expiry_date'][$i],
                        'subtotal' => $data['subtotal'][$i],
                        'purchase_id' => $item->id,
                    ]);
                }else{
                    $order = PurchaseOrder::find($data['order_id'][$i]);
                    $order->update([
                        'supply_id' => $data['supply_id'][$i],
                        'cost' => $data['cost'][$i],
                        'quantity' => $data['quantity'][$i],
                        'expiry_date' => $data['expiry_date'][$i],
                        'subtotal' => $data['subtotal'][$i],
                    ]);
                }
            }
        }
        
        return redirect(route('purchase.index'))->with('success', __('page.updated_successfully'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'purchase_list']);
        $purchase = Purchase::find($id);

        return view('purchase.detail', compact('purchase'));
    }

    public function delete($id){
        $item = Purchase::find($id);
        $item->orders()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
