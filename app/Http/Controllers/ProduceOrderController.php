<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Workshop;
use App\Models\Supply;
use App\Models\Product;
use App\Models\ProduceOrder;
use App\Models\ProduceOrderReception;
use App\Models\ProduceOrderSupply;
use App\Models\Image;

use Auth;
use PDF;

class ProduceOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'produce_order_list']);
        $user = Auth::user();
        $products = Product::all();
        $workshops = Workshop::all();

        $mod = new ProduceOrder();
        $reference_no = $product_id = $workshop_id = $period = $deadline = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('product_id') != ""){
            $product_id = $request->get('product_id');
            $mod = $mod->where('product_id', $product_id);
        }
        if ($request->get('workshop_id') != ""){
            $workshop_id = $request->get('workshop_id');
            $mod = $mod->where('workshop_id', $workshop_id);
        }
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
            $mod = $mod->whereBetween('timestamp', [$from, $to]);
        }
        if ($request->get('deadline') != ""){   
            $deadline = $request->get('deadline');
            $from = substr($deadline, 0, 10);
            $to = substr($deadline, 14, 10);
            $mod = $mod->whereBetween('max_date', [$from, $to]);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $product_array = Product::where('name', 'LIKE', "%$keyword%")->pluck('id');
            $workshop_array = Workshop::where('name', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $supplier_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('product_id', $workshop_array)
                        ->orWhereIn('workshop_id', $workshop_array)
                        ->orWhere('timestamp', 'LIKE', "%$keyword%")
                        ->orWhere('total_cost', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('timestamp', $sort_by_date)->paginate($pagesize);
        return view('produce_order.index', compact('data', 'products', 'workshops', 'product_id', 'workshop_id', 'reference_no', 'period', 'deadline', 'keyword', 'sort_by_date'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_produce_order']);
        $workshops = Workshop::all();
        $products = Product::all();
        $supplies = Supply::all();
        $next_reference_no = str_pad(ProduceOrder::max('id') + 1, 6, "0", STR_PAD_LEFT);
        return view('produce_order.create', compact('workshops', 'products', 'supplies', 'next_reference_no'));
    }

    public function save(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'product'=>'required',
            'workshop'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['supply_id']) ||  count($data['supply_id']) == 0 || in_array(null, $data['supply_id'])){
            return back()->withErrors(['supply' => 'Please select a supply.']);
        }
        // dd($data);
        $item = new ProduceOrder();
        $item->user_id = Auth::user()->id;  
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->product_id = $data['product'];
        $item->workshop_id = $data['workshop'];
        if($data['deadline'] != ''){
            $item->deadline = $data['deadline'];
            $item->max_date = date('Y-m-d', strtotime("+".$data['deadline']."days", strtotime($item->timestamp)));
        }        
        $item->deadline = $data['deadline'];
        $item->description = $data['description'];
        $item->responsibility = $data['responsibility'];
        $item->quantity = $data['product_quantity'];

        if($request->has("main_image")){
            $picture = request()->file('main_image');
            $imageName = time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/produce_images/main_image'), $imageName);
            $item->main_image = 'images/uploaded/produce_images/main_image/'.$imageName;
        }

        

        $item->supply_cost = $data['supply_cost'];
        $item->manufacturing_cost = $data['manufacturing_cost'];
        $item->total_cost = $data['total_cost'];

        $item->save();

        if($request->has("gallery_images")){
            for ($i=0; $i < count($data['gallery_images']); $i++) { 
                $picture = $data['gallery_images'][$i];
                $imageName = time().'.'.$picture->getClientOriginalExtension();
                $picture->move(public_path('images/uploaded/produce_images/gallery_images'), $imageName);
                $image_path = 'images/uploaded/produce_images/gallery_images/'.$imageName;
                Image::create([
                    'path' => $image_path,
                    'imageable_id' => $item->id,
                    'imageable_type' => PurchaseOrder::class,
                ]);
            }            
        }
        if(isset($data['supply_id']) && count($data['supply_id']) > 0){

            for ($i=0; $i < count($data['supply_id']); $i++) { 
                ProduceOrderSupply::create([
                    'supply_id' => $data['supply_id'][$i],
                    'cost' => $data['cost'][$i],
                    'quantity' => $data['quantity'][$i],
                    'subtotal' => $data['subtotal'][$i],
                    'produce_order_id' => $item->id,
                ]);
            }
        }       

        return redirect(route('produce_order.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'produce_order_list']);   
        $order = ProduceOrder::find($id);        
        $products = Product::all();
        $workshops = Workshop::all();
        $supplies = Supply::all();

        return view('produce_order.edit', compact('order', 'workshops', 'supplies', 'products'));
    }

    
    public function update(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'product'=>'required',
            'workshop'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['supply_id']) ||  count($data['supply_id']) == 0 || in_array(null, $data['supply_id'])){
            return back()->withErrors(['supply' => 'Please select a supply.']);
        }
        // dd($data);
        $item = ProduceOrder::find($request->get('id'));
        $item->user_id = Auth::user()->id;  
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->product_id = $data['product'];
        $item->workshop_id = $data['workshop'];
        if($data['deadline'] != ''){
            $item->deadline = $data['deadline'];
            $item->max_date = date('Y-m-d', strtotime("+".$data['deadline']."days", strtotime($item->timestamp)));
        }        
        $item->deadline = $data['deadline'];
        $item->description = $data['description'];
        $item->responsibility = $data['responsibility'];
        $item->quantity = $data['product_quantity'];

        if($request->has("main_image")){
            $picture = request()->file('main_image');
            $imageName = time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/produce_images/main_image'), $imageName);
            $item->main_image = 'images/uploaded/produce_images/main_image/'.$imageName;
        }

        

        $item->supply_cost = $data['supply_cost'];
        $item->manufacturing_cost = $data['manufacturing_cost'];
        $item->total_cost = $data['total_cost'];

        $item->save();

        if($request->has("gallery_images")){
            for ($i=0; $i < count($data['gallery_images']); $i++) { 
                $picture = $data['gallery_images'][$i];
                $imageName = time().'.'.$picture->getClientOriginalExtension();
                $picture->move(public_path('images/uploaded/produce_images/gallery_images'), $imageName);
                $image_path = 'images/uploaded/produce_images/gallery_images/'.$imageName;
                Image::create([
                    'path' => $image_path,
                    'imageable_id' => $item->id,
                    'imageable_type' => PurchaseOrder::class,
                ]);
            }            
        }

        $produce_supplies = $item->supplies->pluck('id')->toArray();
        $diff_supplies = array_diff($produce_supplies, $data['order_id']);
        foreach ($diff_supplies as $key => $value) {
            ProduceOrderSupply::find($value)->delete();
        }

        if(isset($data['supply_id']) && count($data['supply_id']) > 0){

            for ($i=0; $i < count($data['supply_id']); $i++) { 
                if($data['order_id'][$i] == ''){
                    ProduceOrderSupply::create([
                        'supply_id' => $data['supply_id'][$i],
                        'cost' => $data['cost'][$i],
                        'quantity' => $data['quantity'][$i],
                        'subtotal' => $data['subtotal'][$i],
                        'produce_order_id' => $item->id,
                    ]);
                }else{
                    $order = ProduceOrderSupply::find($data['order_id'][$i]);
                    $order->update([
                        'supply_id' => $data['supply_id'][$i],
                        'cost' => $data['cost'][$i],
                        'quantity' => $data['quantity'][$i],
                        'subtotal' => $data['subtotal'][$i],
                    ]);
                }
            }
        }       

        return redirect(route('produce_order.index'))->with('success', __('page.created_successfully'));
    }



    
    public function detail(Request $request, $id){    
        config(['site.page' => 'purchase_list']);
        $order = ProduceOrder::find($id);

        return view('produce_order.detail', compact('order'));
    }

    public function delete($id){
        $item = ProduceOrder::find($id);
        $item->supplies()->delete();
        $item->receives()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function report($id){
        $supplier = Supplier::find($id);
        $pdf = PDF::loadView('produce_order.report', compact('supplier'));
  
        return $pdf->download('supplier_report_'.$supplier->name.'.pdf');
    }
}
