<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationsRelations extends Model
{

	protected $table = 'notifications_relations';

	protected $primaryKey = 'notification_rel_id';

	protected $fillable = [
		'notification_id',
		'user_id',
		'device_id',
		'status'
	];

}
