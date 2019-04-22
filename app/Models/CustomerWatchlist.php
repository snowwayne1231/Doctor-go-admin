<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWatchlist extends Model
{
    protected $append = ['customer', 'product'];
    protected $hidden = ['customer_id', 'id', 'created_at'];

    public function customer() {
        return $this->hasOne('App\Models\Customer');
    }

    public function product() {
        return $this->hasOne('App\Models\Product');
    }
}
