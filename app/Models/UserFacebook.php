<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFacebook extends Model
{
    protected $table = 'users_fb';

	public function saveData($user_id, $facebook_id, $token, $data)
	{

		$this->where('user_id', '=', $user_id)->where('active', '=', 1)->update(['active' => 0]);

		$this->user_id = $user_id;
		$this->facebook_id = $facebook_id;
		$this->token = $token;
		$this->data = json_encode($data);

		$this->save();


	}
}
