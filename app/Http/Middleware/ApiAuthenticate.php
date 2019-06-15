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
        } else {
            if (isset($request->customer['error'])) {

                $msg = $request->customer['error'];

                throw new AuthException($msg);
            }
        }
        return $next($request);
    }
}
