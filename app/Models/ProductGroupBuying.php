<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroupBuying extends Model
{
    // protected $fillable = ['name', 'description'];
    protected $table = 'product_group_buyings';

    protected $casts = [
        'discount_json' => 'json',
    ];

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function product_description() {
        return $this->hasOne('App\Models\ProductDescription', 'product_id', 'product_id');
    }

    public function orders() {
        return $this->hasMany('App\Model\PaymentGroupOrder', 'product_order_id');
    }

    public function getDiscountJsonAttribute($json)
    {
        return array_values(json_decode($json, true) ?: []);
    }

    public function setDiscountJsonAttribute($json)
    {
        $this->attributes['discount_json'] = json_encode(array_values($json));
    }
}
