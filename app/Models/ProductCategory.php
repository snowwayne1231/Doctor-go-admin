<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $appends = ['parentName', 'name'];

    public function description() {
        return $this->hasMany('App\Models\ProductCategoryDescription', 'category_id');
    }

    public function parent() {
        return $this->belongsTo('App\Models\ProductCategory', 'parent_id');
    }

    public function getNameAttribute() {
        return $this->description->first()->name;
    }

    public function getParentNameAttribute() {
        return $this->parent['name'];
    }
}
