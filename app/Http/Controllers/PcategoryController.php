<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pcategory;

class PcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        config(['site.page' => 'pcategory']);
        $data = Pcategory::paginate(15);
        return view('settings.pcategory', compact('data'));
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
        ]);
        $item = Pcategory::find($request->get("id"));
        $item->name = $request->get("name");
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
        ]);
        
        Pcategory::create([
            'name' => $request->get('name'),
        ]);
        return back()->with('success', __('page.created_successfully'));
    }

    public function delete($id){
        $item = Pcategory::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
