<?php

namespace App\Http\Response;

use Illuminate\Support\MessageBag;

class Response extends \Response
{

	public static function success($data)
	{

		if($data instanceof MessageBag)
		{
			$return = [];
			foreach($data->getMessages() as $key => $val)
			{

				$return[$key] = $val[0];
			}
			$data = $return;
		}

		if(is_string($data))
			$data = [$data];

		return self::json([
			'status' => 1,
			'responseData' => $data
		], 200);
	}

	public static function error($messages)
	{


		if($messages instanceof MessageBag)
		{
			$return = [];
			foreach($messages->getMessages() as $key => $val)
			{

				$return[$key] = $val[0];
			}
			$messages = $return;
		}


		if(is_string($messages))
			$messages = [$messages];

		return self::json([
			'status' => 0,
			'messages' => $messages
		], 200);

	}

}