<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    public function product_sales() {
        return $this->hasMany(ProductSale::class);
    }

    public function produce_orders() {
        return $this->hasMany(ProduceOrder::class);
    }
}
