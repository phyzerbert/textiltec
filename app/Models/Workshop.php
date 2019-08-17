<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $guarded = [];

    public function produce_orders(){
        return $this->hasMany('App\Models\ProduceOrder');
    }
}
