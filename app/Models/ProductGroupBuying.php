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
        return $this->hasMany('App\Models\PaymentGroupOrder', 'product_order_id');
    }

    public function getDiscountJsonAttribute($json)
    {
        return array_values(json_decode($json, true) ?: []);
    }

    public function setDiscountJsonAttribute($json)
    {
        $this->attributes['discount_json'] = json_encode(array_values($json));
    }

    public function getPriceAttribute()
    {
        $json = json_decode($this->attributes['discount_json'], true);
        $quantity = intval($this->attributes['sum_quantity']);
        if (isset($json[0])) {
            $loc = $json[0];

            foreach ($json as $j) {
                if($quantity >= intval($j['condition_quantity'])) {
                    $loc = $j;
                }
            }
            $price = intval($loc['price']);
        } else {
            $price = $this->product['price'];
        }
        
        
        return $price;
    }
}
