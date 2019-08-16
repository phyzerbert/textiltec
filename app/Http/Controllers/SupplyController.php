<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supply;
use App\Models\Scategory;
use App\Models\Supplier;

class SupplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        config(['site.page' => 'supply_list']);
        $categories = Scategory::all();
        $mod = new Supply();
        $code = $name = $category_id = '';
        if ($request->get('code') != ""){
            $code = $request->get('code');
            $mod = $mod->where('code', 'LIKE', "%$code%");
        }
        if ($request->get('name') != ""){
            $name = $request->get('name');
            $mod = $mod->where('name', 'LIKE', "%$name%");
        }
        if ($request->get('category_id') != ""){
            $category_id = $request->get('category_id');
            $mod = $mod->where('category_id', $category_id);
        }
        $pagesize = session('pagesize');
        if(!$pagesize){$pagesize = 15;}
        $data = $mod->orderBy('created_at', 'desc')->paginate($pagesize);
        return view('supply.index', compact('data', 'categories', 'code', 'name', 'category_id'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_supply']);
        $categories = Scategory::all();
        $suppliers = Supplier::all();
        return view('supply.create', compact('categories', 'suppliers'));        
    }

    public function save(Request $request){
        $request->validate([
            'name'=>'required|string',
            'code'=>'required|string',
            'category'=>'required',
            'unit'=>'required|string',
            'cost'=>'required|numeric',
            'supplier'=>'required',
        ]);
        $data = $request->all();
        // dd($data);
        $item = new Supply();
        $item->name = $data['name'];
        $item->code = $data['code'];
        $item->category_id = $data['category'];
        $item->unit = $data['unit'];
        $item->cost = $data['cost'];
        $item->color = $data['color'];
        $item->alert_quantity = $data['alert_quantity'];
        $item->supplier_id = $data['supplier'];
        $item->detail = $data['detail'];

        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_images/'), $imageName);
            $item->image = 'images/uploaded/product_images/'.$imageName;
        }
        $item->save();

        return back()->with('success', __('page.created_successfully'));
    }

    public function detail(Request $request, $id){
        config(['site.page' => 'supply_list']);
        $supply = Supply::find($id);

        return view('supply.detail', compact('supply'));
    }

    public function edit(Request $request, $id){
        config(['site.page' => 'supply_list']);
        $supply = Supply::find($id);
        $categories = Scategory::all();
        $suppliers = Supplier::all();

        return view('supply.edit', compact('supply', 'categories', 'suppliers'));
    }

    
    public function update(Request $request){
        $request->validate([
            'name'=>'required|string',
            'code'=>'required|string',
            'category_id'=>'required',
            'unit'=>'required|string',
            'cost'=>'required|numeric',
            'supplier_id'=>'required',
        ]);
        $data = $request->all();
        $item = Supply::find($request->get("id"));
        $data['image'] = $item->image;

        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_images/'), $imageName);
            $data['image'] = 'images/uploaded/product_images/'.$imageName;
        }
        $item->update($data);
        return redirect(route('supply.index'))->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Supply::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
