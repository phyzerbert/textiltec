<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        config(['site.page' => 'product']);
        $data = Product::paginate(15);
        return view('product.index', compact('data'));
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
        ]);
        
        $item = Product::find($request->get("id"));
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_images/'), $imageName);
            $item->image = 'images/uploaded/product_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
        ]);

        $item = new Product();
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_images/'), $imageName);
            $item->image = 'images/uploaded/product_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.created_successfully'));
    }

    public function delete($id){
        $item = Product::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
