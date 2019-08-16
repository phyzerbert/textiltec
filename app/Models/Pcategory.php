<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pcategory extends Model
{
    protected $guarded = [];

    public function product(){
        return $this->hasMany('App\Models\Product');
    }
}
