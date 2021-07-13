<?php

namespace App\Services;


use App\Jobs\Mail;
use App\Jobs\NotificationEmail;
use App\Jobs\NotificationsApple;
use App\Jobs\NotificationsEmail;
use App\Models\Installations;
use App\Models\NotificationsRelations;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;

class NotificationsService
{

	use DispatchesJobs;

	public function send($user_id, $type = 0, $data = [], $parent_id = 0)
	{

//		$notifications = new \App\Models\Notifications();
//		$notification = $notifications->add($data['message'], $data, $type, $parent_id);
//
//		$users_push = Installations::where('user_id', '=', $user_id)->where('send_notifications', '=', 1)->where('status', '=', 1)->get();
//
//
//		if($users_push)
//			foreach ( $users_push as $user )
//			{
//
//				NotificationsRelations::create([
//					'notification_id' => $notification['notification_id'],
//					'device_id' => $user->device_id,
//					'user_id' => $user_id
//				]);
//
//				$this->dispatch(New NotificationsApple([
//					'token' => $user->ios_token,
//					'notification_id' => $notification['notification_id'],
//					'message' => $data['message'],
//					'status' => 1,
//					'type' => $type,
//					'created_at' => (is_null($notification['created_at'])) ? date('Y-m-d H:i:s') : $notification['created_at'],
//					'data' => $data
//				]));
//
//			}

		if($type == 2)
		{
			$user_email = User::where( 'user_id', '=', $user_id )->where( 'status', '=', 1 )->first();
			if($user_email)
			{

				$this->dispatch(new Mail([
					'type' => Mail::FLIGHT_ALERT,
					'data' => [
						'id' => $data['flight_id'],
						'origin' => $data['origin'],
						'destination' => $data['destination'],
					],
					'user' => [
						'first_name' => $user_email->first_name,
						'last_name' => $user_email->last_name,
						'email' => $user_email->email
					]
				]));
				return TRUE;
			}

		}

	}


}