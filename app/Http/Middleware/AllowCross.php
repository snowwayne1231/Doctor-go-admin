<?php

namespace App\Http\Middleware;

use Closure;

class AllowCross
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header("Access-Control-Allow-Origin: *");

        // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-UUID, X-Auth-Token, Origin'
        ];

        if($request->getMethod() == "OPTIONS") {
            $response = \Response::make('OK', 200);
        } else {
            $response = $next($request);
        }


        foreach($headers as $key => $value)
            $response->header($key, $value);
        
        return $response;
    }
}
