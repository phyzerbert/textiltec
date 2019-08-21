<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Workshop extends Model
{
    protected $guarded = [];

    public function produce_orders(){
        return $this->hasMany('App\Models\ProduceOrder');
    }

    public function products(){
        $products = $this->produce_orders->pluck('product_id');
        $mod = new Product();
        return $mod->whereIn('id', $products);
    }
    
    public function payments(){
        return $this->morphMany('App\Models\Payment', 'paymentable');
    }
    
}
