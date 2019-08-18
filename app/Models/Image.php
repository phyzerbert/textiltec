<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function produce_orders()
    {
        return $this->morphedByMany('App\Models\ProduceOrder', 'imageable');
    }
}
