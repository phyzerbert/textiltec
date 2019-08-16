<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $guarded = [];

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function category(){
        return $this->belongsTo('App\Models\Scategory', 'category_id');
    }

    public function purchase_orders(){
        return $this->hasMany('App\Models\PurchaseOrder');
    }
}
