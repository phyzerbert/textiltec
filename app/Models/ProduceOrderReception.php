<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduceOrderReception extends Model
{
    protected $guarded = [];

    public function produce_order(){
        return $this->belongsTo('App\Models\ProudceOrder');
    }
}
