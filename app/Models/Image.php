<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    static function saveByImageFile($name, $file) {
        $self = new self;
        $timestamp = time();
        $randomName = $timestamp.'.'.urlencode($name);
        
        $path = \Storage::disk('public')->putFileAs('', $file, $randomName);

        $self->uri = $path;

        $self->save();

        return $self;
    }

    public function deleteImage() {
        \Storage::disk('public')->delete($this->uri);

        $uri = storage_path($this->uri);
        if(is_file($uri)){
            unlink($uri);
        }
        
        $this->delete();
        return $this;
    }

    
}
