<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create(Request $request){
        config(['site.page' => 'add_purchase']);
        $suppliers = Supplier::all();
        $supplies = Supply::all();
        return view('purchase.create', compact('suppliers', 'stores', 'products'));
    }
}
