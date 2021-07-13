<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{

    public function index()
    {

        $agent = new Agent();
        $browser = $agent->browser();
        $version = $agent->version($browser);
        $old = false;

        switch ($browser){
            case 'Safari':
                $version = substr($version, 0, 3);
                $version = str_replace('.', '', $version);
                if($version < 91){
                    $old = true;
                }
                break;
            case 'Chrome':
                if(substr($version, 0, 2) < 50){
                    $old = true;
                }
                break;
            case 'Firefox':
                if(substr($version, 0, 2) < 47){
                    $old = true;
                }
                break;
            default:
                $old = true;
                break;
        }



        return \View::make('admin.dashboard', [
            'old' => $old
        ]);
    }

}
