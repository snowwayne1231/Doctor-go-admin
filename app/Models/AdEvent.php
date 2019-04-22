<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdEvent extends Model
{
    
    public function getProductIdsAttribute($value) {
        return explode(',', $value);
    }

    public function setProductIdsAttribute($value) {
        $this->attributes['product_ids'] = implode(',', $value);
    }
}
