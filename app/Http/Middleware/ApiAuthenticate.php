<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\AuthException;

class ApiAuthenticate
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
        if (empty($request->customer)) {
            throw new AuthException('需要登入');
        }
        return $next($request);
    }
}
