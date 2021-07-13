<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Prologue\Alerts\Facades\Alert;

class Admin
{

    public $resources = [
        'admin' => [
            'Admin\DashboardController' => [
                'index'
            ]
        ],
        'operator' => [
            'Admin\DashboardController' => [
                'index'
            ]
        ]
    ];


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

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        $user_role = $this->auth->user()->role;

        if(!in_array($user_role, ['admin', 'operator'])) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }


        $action = explode('@', $request->route()->getActionName());

        $action[0] = str_replace('App\Http\Controllers\\', '', $action[0]);

//        echo '<pre>';
//        print_r($action);
//        die();

        return $next($request);

    }
}
