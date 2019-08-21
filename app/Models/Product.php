<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function produce_order(){
        return $this->hasOne('App\Models\ProduceOrder');
    }

    public function sale_orders(){
        return $this->hasMany('App\Models\SaleOrder');
    }

    public function category(){
        return $this->belongsTo('App\Models\Pcategory', 'category_id');
    }
}
