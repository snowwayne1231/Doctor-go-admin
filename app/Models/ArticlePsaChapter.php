<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlePsaChapter extends Model
{
    //
    protected $fillable = ['title', 'image', 'sort', 'enable', 'public_date'];
    protected $hidden = ['created_at', 'magazine_id'];

    public function magazine() {
        return $this->belongsTo('App\Models\ArticlePsaMagazine', 'magazine_id');
    }

    public function description() {
        return $this->hasOne('App\Models\ArticlePsaChapterDescription', 'chapter_id');
    }
    
    // public function getContentAttibute($content) {
    //     return $this->desctipion->content;
    // }

    // public function setContentAttibute() {
    //     $this->desctipion->content = ;
    // }
}
