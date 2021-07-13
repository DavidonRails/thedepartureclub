<?php

namespace App\Http\Middleware;

use App\Models\UserTokens;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $cookie_token = \Cookie::get('token');
        if(!$cookie_token)
            return redirect()->guest('auth/login');

        $token = $cookie_token;

        $user = UserTokens::checkToken($token);


        if(!$user)
            return redirect()->guest('auth/login');


        \Auth::loginUsingId($user->user_id, TRUE);
		
		$user_data = \Auth::getUser();

		if($user && $user_data['status'] == '2') {
			if($request->route()->uri() !== 'membership') {
				return redirect('/membership');
			} else {
				return $next($request);
			}
			
		}

        return $next($request);
        
//
//        if ($this->auth->guest()) {
//            if ($request->ajax()) {
//                return response('Unauthorized.', 401);
//            } else {
//                return redirect()->guest('auth/login');
//            }
//        }
//
//        return $next($request);
    }
}
