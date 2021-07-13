<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;

class UsersPasswordResets extends Model
{

	protected $table = 'users_password_resets';

	protected $fillable = ['email', 'token', 'created_at'];


	public function setUpdatedAtAttribute($value)
	{
		return NULL;
	}

}
