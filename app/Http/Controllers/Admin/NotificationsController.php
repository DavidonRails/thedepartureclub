<?php

namespace App\Http\Controllers\Admin;;

use App\Http\Response\Response;
use App\Jobs\NotificationsApple;
use App\Models\Installations;
use App\Models\InstallationsRelations;
use App\Models\Notifications;
use App\Models\NotificationsRelations;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{


    public function add( Request $request )
    {

        $data = $request->only(['user_id', 'type', 'message']);

        $validate = $this->validateNewNotification($data);

        if($validate->fails())
        {
            return Response::error($validate->getMessageBag());
        }

        if(empty($data['type']))
            $data['type'] = 0;


        $notifications = new Notifications();

        $notification = $notifications->add($data['message'], [], $data['type']);

        if($notification['notification_id'])
        {
            $this->_addToQueue(
                $data['user_id'],
                $notification['notification_id'],
                $data['message'],
                $data['type'],
                [],
                $notification['created_at']);
        }


        return Response::success(['message' => 'Notification added']);

    }


    private function _addToQueue($user_id, $notification_id, $message, $type, $data = [], $created_at = NULL)
    {

        // REL NOTIFICATION USER NOT DEVICE ID

        if($user_id == 0)
            $users = Installations::get();
        else
            $users = Installations::where('user_id', '=', $user_id)->get();

        if($user_id != 0 && !count($users))
        {
            NotificationsRelations::create([
                'notification_id' => $notification_id,
                'device_id' => '',
                'user_id' => $user_id
            ]);
        }


        foreach($users as $user)
        {

            NotificationsRelations::create([
                'notification_id' => $notification_id,
                'device_id' => $user->device_id,
                'user_id' => $user_id
            ]);

            $this->dispatch(new NotificationsApple([
                'token' => $user->ios_token,
                'notification_id' => $notification_id,
                'message' => $message,
                'status' => 1,
                'type' => $type,
                'created_at' => (is_null($created_at)) ? date('Y-m-d H:i:s') : $created_at,
                'data' => json_encode($data)
            ]));
            

        }

    }

    public function get()
    {

        $notifications = new Notifications();

        $notifications_list = $notifications->getForAdmin();

        return Response::success($notifications_list);
    }

    protected function validateNewNotification(array $data)
    {

        return \Validator::make($data, [
            'message' => 'required|min:2|max:255'
        ]);

    }

}
