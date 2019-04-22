<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $attributes = [
        'first_name' => '',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

}
