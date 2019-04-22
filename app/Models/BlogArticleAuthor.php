<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogArticleAuthor extends Model
{
    //
    public function description() {
        return $this->hasMany('App\Models\BlogArticleAuthorDescription', 'author_id');
    }
}
