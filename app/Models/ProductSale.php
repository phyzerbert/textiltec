<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $guarded = [];

    public function orders() {
        return $this->hasMany('App\Models\SaleOrder');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function payments(){
        return $this->morphMany('App\Models\Payment', 'paymentable');
    }

    public function images() {
        return $this->morphMany('App\Models\Image', 'imageable');
    }


}
