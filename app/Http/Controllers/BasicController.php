<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicController extends Controller
{
    //
    public function basicJSON($input)
    {
        $result = [
            'result' => $input,
            'errors' => '',
            'status' => 0,
            'time' => time(),
        ];

        return response($result, 200)->header('Content-Type', 'application/json');
    }

}

