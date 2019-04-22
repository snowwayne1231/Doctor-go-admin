<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchlistArticle extends Model
{
    protected $append = ['customer', 'article'];
    protected $hidden = ['customer_id', 'id', 'created_at'];
    
    public function customer() {
        return $this->hasOne('App\Models\Customer');
    }

    public function article() {
        return $this->hasOne('App\Models\BlogArticle');
    }
}
