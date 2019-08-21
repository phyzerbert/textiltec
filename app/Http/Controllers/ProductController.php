<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use App\Models\Pcategory;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        config(['site.page' => 'product']);
        $categories = Pcategory::all();
        $data = Product::paginate(15);
        return view('product.index', compact('data', 'categories'));
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
            'code'=>'required',
        ]);
        
        $item = Product::find($request->get("id"));
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->unit = $request->get("unit");
        $item->category_id = $request->get("category_id");
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
            'code'=>'required',
        ]);

        $item = new Product();
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->unit = $request->get("unit");
        $item->category_id = $request->get("category_id");
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
        // dump($item->produce_order);
        if($item->produce_order == null){
            $item->delete();
        }else{            
            return back()->withErrors(['product' => __('page.delete_product_error')]);
        }        
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function produce_create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'code'=>'required',
        ]);
        $item = new Product();
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->unit = $request->get("unit");
        $item->category_id = $request->get("category_id");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/product_images/'), $imageName);
            $item->image = 'images/uploaded/product_images/'.$imageName;
        }
        $item->save();
        return response()->json($item);
    }
}
