<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;

use PDF;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        config(['site.page' => 'customer']);
        $mod = new Customer();
        $data = $mod->orderBy('created_at', 'desc')->get();
        return view('customer.index', compact('data'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        Customer::create([
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

    public function sale_create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        $customer = Customer::create([
            'name' => $request->get('name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'note' => $request->get('note'),
        ]);

        return response()->json($customer);
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
            'company'=>'required|string',
        ]);
        $item = Customer::find($request->get("id"));
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
        $item = Customer::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function report($id)
    {
        $customer = Customer::find($id);
        $pdf = PDF::loadView('customer.report', compact('customer'));
  
        return $pdf->download('customer_report_'.$customer->name.'.pdf');
        // return view('customer.report', compact('customer'));
    }
}
