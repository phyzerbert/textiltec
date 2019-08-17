<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduceOrderSupply extends Model
{
    protected $guarded = [];

    public function produce_order(){
        return $this->belongsTo('App\Models\ProduceOrder');
    }

    public function supply(){
        return $this->belongsTo('App\Models\Supply');
    }
}
