<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;
use App\Models\Product;
use App\Models\ProduceOrder;
use App\Models\Payment;
use App\Models\ProduceOrderReception;

class WorkshopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        config(['site.page' => 'workshop']);
        $data = Workshop::paginate(15);
        return view('workshop.index', compact('data'));
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
        ]);
        $item = Workshop::find($request->get("id"));
        $item->name = $request->get("name");
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
        ]);
        
        Workshop::create([
            'name' => $request->get('name'),
        ]);
        return back()->with('success', __('page.created_successfully'));
    }

    public function delete($id){
        $item = Workshop::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function produce_order(Request $request, $id) {
        config(['site.page' => 'workshop']);
        $workshop = Workshop::find($id);
        $products = Product::all();
        $mod = $workshop->produce_orders();
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
        $data = $mod->orderBy('created_at', 'desc')->paginate(15);
        return view('workshop.order', compact('data', 'id', 'products', 'workshops', 'product_id', 'workshop_id', 'reference_no', 'period', 'deadline', 'keyword', 'sort_by_date'));
    }

    public function receiption(Request $request, $id) {
        config(['site.page' => 'workshop']);
        $workshop = Workshop::find($id);
        $produce_order_array = $workshop->produce_orders->pluck('id');
        $mod = new ProduceOrderReception();
        $mod = $mod->whereIn('produce_order_id', $produce_order_array);
        $data = $mod->orderBy('receive_date')->paginate(15);
        return view('workshop.receiption', compact('data', 'id'));
    }

    public function payment(Request $request, $id) {
        config(['site.page' => 'workshop']);
        $workshop = Workshop::find($id);
        $produce_order_array = $workshop->produce_orders->pluck('id');
        $mod = new Payment();
        $mod = $mod->whereIn('paymentable_id', $produce_order_array)->where('paymentable_type', 'App\Models\ProduceOrder');
        $data = $mod->orderBy('timestamp', 'desc')->paginate(15);
        return view('workshop.payment', compact('data', 'id'));
    }
}
