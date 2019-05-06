<?php

namespace App\Http\Middleware;

use Closure;

class NiceArtisan
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
        // $user = $request->user();

        $cookie = $request->cookie('snow');

        if ($cookie == 'snowWayne') {
            return $next($request);
        }

        return response('snow', 403);
    }
}