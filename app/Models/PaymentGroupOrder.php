<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGroupOrder extends Model
{
    // protected $fillable = ['name', 'description'];

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    // public function customer_description() {
    //     return $this->hasOne('App\Models\CustomerDescription', 'customer_id', 'customer_id');
    // }
    
}
