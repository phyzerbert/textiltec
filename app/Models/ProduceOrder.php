<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduceOrder extends Model
{
    protected $guarded = [];

    public function workshop(){
        return $this->belongsTo('App\Models\Workshop');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function supplies(){
        return $this->hasMany('App\Models\ProduceOrderSupply');
    }

    public function receptions(){
        return $this->hasMany('App\Models\ProduceOrderReception');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }
        
    public function payments(){
        return $this->morphMany('App\Models\Payment', 'paymentable');
    }
}
