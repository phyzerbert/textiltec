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
            'price'=>'required',
        ]);
        
        $item = Product::find($request->get("id"));
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->price = $request->get("price");
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
            'price'=>'required|numeric',
        ]);

        $item = new Product();
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->price = $request->get("price");
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
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function produce_create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'code'=>'required',
            'price'=>'required|numeric',
        ]);
        $item = new Product();
        $item->name = $request->get("name");
        $item->code = $request->get("code");
        $item->price = $request->get("price");
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
