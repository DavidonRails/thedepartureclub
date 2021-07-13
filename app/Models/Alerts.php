<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Alerts extends Model
{

	public $primaryKey = 'alert_id';

	public function get($user_id)
	{

		$result = $this->where('user_id', '=', $user_id)->where('active', '=', 1)->get([
			'alert_id',
			'origin',
			'destination'
		]);

		if($result)
			return $result;
		else
			return [];

	}
	public function add($user_id, $data)
	{

		$this->user_id = $user_id;
		$this->origin = $data['origin'];
		$this->origin_longitude = $data['origin_longitude'];
		$this->origin_latitude = $data['origin_latitude'];
		$this->destination = $data['destination'];
		$this->destination_longitude = $data['destination_longitude'];
		$this->destination_latitude = $data['destination_latitude'];
		$this->active = 1;

		$this->save();

		return $this->alert_id;

    }

	public function del($user_id, $alert_id)
	{

		$result = $this->where('user_id', '=', $user_id)->where('alert_id', '=', $alert_id)->where('active', '=', 1)->get();

		if($result && $result->count())
		{
			foreach($result as $r)
			{
				$r->active = 0;
				$r->update();
			}

			return TRUE;
		}

		return FALSE;

	}
}
