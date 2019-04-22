<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Storage;

class UploadController extends Controller
{
    public function image(Request $request) {

        // $dir = config('admin.extensions.wang-editor.config.uploadImgServer');

        $images = $request->file('image');
        $result = [
            'errno' => 0,
            'data' => [],
        ];

        foreach ($images as $idx => $img) {
            $name = $img->getClientOriginalName();
            $timestamp = time();
            $randomName = $timestamp;
            
            $path = Storage::disk('admin')->putFileAs('images', $img, $randomName);
            $prefix = config('filesystems.disks.admin.url');

            $result['data'][] = $prefix.$path;
        }
        
        return response($result);
    }
}
