<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;
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
        config(['site.page' => 'produce_order']);
        $workshop = Workshop::find($id);
        $mod = $workshop->produce_orders();
        $data = $mod->orderBy('created_at', 'desc')->paginate(15);
        return view('workshop.order', compact('data', 'id'));
    }
}
