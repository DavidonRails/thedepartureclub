<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Installations;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsNotifications extends Controller
{

    public function status( Request $request )
    {

        $device_id = $request->only(['device_id'])['device_id'];


        if(Installations::notificationsStatus($device_id))
            return Response::success([
                'message' => 'Notifications active'
            ]);
        else
            return Response::error([
                'message' => 'Notifications inactive'
            ]);

    }

    public function on(Request $request)
    {
        $device_id = $request->only(['device_id'])['device_id'];

        Installations::notificationsOn($device_id);

        return Response::success([
            'message' => 'Settings updated'
        ]);
    }

    public function off(Request $request)
    {
        $device_id = $request->only(['device_id'])['device_id'];

        Installations::notificationsOff($device_id);

        return Response::success([
            'message' => 'Settings updated'
        ]);

    }

}
