<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlePsaMagazine extends Model
{
    //
    public function chapters() {
        return $this->hasMany('App\Models\ArticlePsaChapter', 'magazine_id');
    }

}
