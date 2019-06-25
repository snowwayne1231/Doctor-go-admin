<?php

namespace App\Http\Controllers\Exception;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;

use App\Models\Error;

class ErrorController extends BasicController
{
    public function storage(Request $request) {
        $inputs = $this->validate($request, [
            'msg' => 'required',
            'line' => 'required',
            'url' => 'max:255',
        ]);

        $error = new Error();
        $error->ip = $request->ip();
        $error->msg = $inputs['msg'];
        $error->line = $inputs['line'];
        $error->url = $inputs['url'];
        $error->save();

        return $this->basicJSON('ok');

    }

}

