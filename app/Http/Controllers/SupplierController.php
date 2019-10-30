<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

use PDF;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        config(['site.page' => 'supplier']);
        $mod = new Supplier();
        $data = $mod->orderBy('created_at', 'desc')->get();
        return view('supplier.index', compact('data'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        Supplier::create([
            'name' => $request->get('name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'note' => $request->get('note'),
        ]);
        return response()->json('success');
    }

    public function purchase_create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        $supplier = Supplier::create([
            'name' => $request->get('name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'note' => $request->get('note'),
        ]);

        return response()->json($supplier);
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
            'company'=>'required|string',
        ]);
        $item = Supplier::find($request->get("id"));
        $item->name = $request->get("name");
        $item->company = $request->get("company");
        $item->email = $request->get("email");
        $item->phone_number = $request->get("phone_number");
        $item->address = $request->get("address");
        $item->city = $request->get("city");
        $item->note = $request->get("note");
        $item->save();
        return response()->json('success');
    }

    public function delete($id){
        $item = Supplier::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function report($id)
    {
        $supplier = Supplier::find($id);
        $pdf = PDF::loadView('supplier.report', compact('supplier'));
  
        return $pdf->download('supplier_report_'.$supplier->name.'.pdf');    
        // return view('supplier.report', compact('supplier'));
    }

}
