<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    protected $guarded = [];

    public function product_sale() {
        return $this->belongsTo('App\Models\ProductSale');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

}
