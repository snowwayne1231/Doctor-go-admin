<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCart extends Model
{
    protected $fillable = ['customer_id', 'ip'];
    protected $hidden = ['id', 'customer_id', 'ip'];
}
