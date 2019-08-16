<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scategory extends Model
{
    protected $guarded = [];

    public function supply(){
        return $this->hasMany('App\Models\Supply');
    }
}
