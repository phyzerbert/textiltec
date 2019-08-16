<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Scategory;

class ScategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        config(['site.page' => 'scategory']);
        $data = Scategory::paginate(15);
        return view('settings.scategory', compact('data'));
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
        ]);
        $item = Scategory::find($request->get("id"));
        $item->name = $request->get("name");
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
        ]);
        
        Scategory::create([
            'name' => $request->get('name'),
        ]);
        return back()->with('success', __('page.created_successfully'));
    }

    public function delete($id){
        $item = Scategory::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
