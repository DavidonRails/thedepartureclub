<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

/**
 * App\UserTokens
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class UserTokens extends Model
{
	protected $table = 'users_tokens';


	public function saveToken($user_id, $token, $type, $by_facebook = FALSE)
	{

//		$this->where('user_id', '=', $user_id)->where('active', '=', 1)->where('type', '=', $type)->delete();

		$user_agent = \Request::header('User-Agent');

		$this->user_id = $user_id;
		$this->token = $token;
		$this->active = 1;
		$this->type = $type;
		$this->ip_address = \Request::ip();
		$this->user_agent = (!empty($user_agent)) ? $user_agent : '';

		if($by_facebook)
			$this->created_by = 'facebook';
		
		if($type == 'web' || $type == '')
		{
			\Cookie::queue('token', $token, 525600);
		}

		return $this->save();

	}

	public function dumpToken( $token )
	{
		$this->where('token', '=', $token)->delete();

	}

	public static function checkToken($token)
	{

		$static = new static;

		$user = $static->where('token', '=', $token)->where('active', '=', 1)->get(['user_id'])->first();

		return $user;

	}

}
