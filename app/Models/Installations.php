<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installations extends Model
{

	protected $primaryKey = 'installation_id';

	protected $fillable = [
		'installation_id',
		'user_id',
		'ios_token',
		'device_id',
		'send_notifications',
		'status',
		'hash'
	];


	public function installation($push_token, $device_id)
	{

		$installation = Installations::where('device_id', '=', $device_id);

		if($installation->count())
		{
			Installations::where('device_id', '=', $device_id)->update([
				'ios_token' => $push_token
			]);
			return TRUE;
		}

		$this->ios_token = $push_token;
		$this->device_id = $device_id;
		$this->send_notifications = 1;
		$this->save();
		
		return $this->installation_id;

	}


	public static function connect( $device_id, $user_id )
	{

		Installations::where('device_id', '=', $device_id)->update([
			'user_id' => $user_id,
			'status' => 1,
			'hash' => md5($device_id . $user_id)
		]);

	}

	public static function notificationsStatus($device_id)
	{

		$data = Installations::where('device_id', '=', $device_id)->get(['send_notifications'])->toArray();
		
		if(count($data) && $data[0]['send_notifications'])
			return TRUE;
		else
			return FALSE;

	}


	public static function notificationsOn( $device_id )
	{
		$data = Installations::where('device_id', '=', $device_id)->update([
			'send_notifications' => 1
		]);

	}
	public static function notificationsOff( $device_id )
	{
		$data = Installations::where('device_id', '=', $device_id)->update([
			'send_notifications' => 0
		]);

	}

}
