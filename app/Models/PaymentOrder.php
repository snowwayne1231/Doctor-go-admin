<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    protected $hidden = [
        'payment_name',
        // 'payment_address',
        // 'payment_city',
        'payment_code',
        'payment_country_id',
        'payment_method',
        'payment_zone_id',
        // 'payment_postcode',
        'shipping_address',
        'shipping_city',
        'shipping_code',
        'shipping_country_id',
        'shipping_method',
        'shipping_name',
        'shipping_postcode',
        'shipping_zone_id',
        'store_id',
        'store_name',
        'store_url',
        // 'customer_email',
        'customer_group_id',
        'customer_id',
        'customer_tax',
    ];

    public function customer() {
        return $this->belongsTo('App\Models\Customer');
    }

    public function products() {
        return $this->hasMany('App\Models\PaymentOrderProduct', 'order_id');
    }

    public function promotion() {
        return $this->hasOne('App\Models\SettingPromotion', 'id', 'promotion_id');
    }
}
