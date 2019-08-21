<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function purchase()
    {
        return $this->morphedByMany('App\Models\PurchaseOrder', 'paymentable');
    }

    public function produce_order()
    {
        return $this->morphedByMany('App\Models\ProduceOrder', 'paymentable');
    }

    public function product_sale()
    {
        return $this->morphedByMany('App\Models\ProductSale', 'paymentable');
    }
}
