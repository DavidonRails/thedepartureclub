<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Password
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
        $cookie = \Cookie::get('password-access');
        if ($cookie) {
            return $next($request);
        }

        return redirect()->guest('auth/password');
    }

}
