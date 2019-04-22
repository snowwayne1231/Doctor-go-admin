<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    // protected $append = ['brand'];

    public function description()
    {
        return $this->hasMany('App\Models\ProductDescription');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ProductCategory', 'category_id');
    }

    public function category_first_description() {
        return $this->hasOne('App\Models\ProductCategoryDescription', 'category_id', 'category_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Models\ProductBrand', 'brand_id');
    }

    public function setImageDetailAttribute($images)
    {
        if (is_array($images)) {
            $this->attributes['image_detail'] = json_encode($images);
        }
    }

    public function getImageDetailAttribute($images)
    {
        return json_decode($images, true);
    }

    // public function getImageBAttribute($value)
    // {
    //     return Image::find($value)->uri;
    // }

    // public function setImageBAttribute($value)
    // {
    //     $image = new Image();
    //     $image->uri = $value;
    //     $image->save();
    //     $this->image_id = $image->id;
    // }
}
