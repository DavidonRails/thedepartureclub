<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\NotificationsRelations;
use App\Models\UserTokens;
use Illuminate\Http\Request;
use App\Models\Notifications;

class NotificationsController extends Controller
{

    public function get(Request $request)
    {

        $user_id = 0;

        $data = $request->only(['token', 'device_id']);
        if(!empty($data['token']))
        {
            $user = UserTokens::checkToken($data['token']);
            if($user)
            {
                \Auth::loginUsingId($user->user_id, TRUE);
                $user_id = \Auth::getUser()->user_id;
            }
        }

        $notification = new Notifications();

        $notifications_data = $notification->get($data['device_id'], $user_id);

        return Response::success($notifications_data);
        
    }


    public function del( Request $request )
    {

        $data = $request->only(['notification_id', 'device_id']);

        NotificationsRelations::where('notification_rel_id', '=', $data['notification_id'])
        ->where('device_id', '=', $data['device_id'])
        ->update([
            'status' => 0
        ]);

        return Response::success(['message' => 'Success']);

    }
    
    
}
