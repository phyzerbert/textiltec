<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function purchase(){
        return $this->belongsTo('App\Models\Purchase');
    }
}
