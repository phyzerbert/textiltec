<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }

    public function orders(){
        return $this->hasMany('App\Models\PurchaseOrder');
    }
}
