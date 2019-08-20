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
        $produce_orders = $this->produce_orders->pluck('id');
        $mod = new Product();
        return $mod->whereIn('id', $produce_orders);
    }
    
}
