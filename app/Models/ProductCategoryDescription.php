<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryDescription extends Model
{
    //
    protected $fillable = ['language_id', 'name', 'description'];

    public function language() {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
