<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function produce_orders(){
        return $this->hasMany('App\Models\ProduceOrder');
    }

    public function sale_orders(){
        return $this->hasMany('App\Models\SaleOrder');
    }
}
