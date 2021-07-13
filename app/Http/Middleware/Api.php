<?php

namespace App\Http\Middleware;

use App\Http\Response\Response;
use App\Models\UserTokens;
use Closure;
use Illuminate\Contracts\Auth\Guard;


class Api
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

        $api_token = $request->only('token');

        $cookie_token = \Cookie::get('token');

        if(!$api_token['token'] && !$cookie_token)
            return Response::error('No token key');

        if(isset($api_token['token']))
            $token = $api_token['token'];
        elseif($cookie_token)
            $token = $cookie_token;

        $user = UserTokens::checkToken($token);

        if(!$user)
            return Response::error('Bad api key');

        \Auth::loginUsingId($user->user_id, TRUE);

        return $next($request);
    }


}
