<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $guarded = [];

    public function supply(){
        return $this->belongsTo('App\Models\Supply');
    }
}
