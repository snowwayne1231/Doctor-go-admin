<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    //
    protected $fillable = ['language_id', 'name', 'description', 'meta_description'];
    protected $hidden = ['final_editor_id'];

    public function language() {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

    // public function getDescriptionAttribute($description) {
    //     return json_decode($description, true);
    // }

    // public function setDescriptionAttribute($description) {
    //     if (is_array($description)) {
    //         $this->attributes['image_detail'] = json_encode($description);
    //     }
    // }

}
