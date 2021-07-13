<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{

	/**
	 * Notifications types
	 *
	 * - 0 genetic
	 * - 1 flight deal ( flight_id | when match alert with flight )
	 * - 2 booking ( booking_id | when operator approve )
	 * - 3 rewards (promo)
	 */


	protected $primaryKey = 'notification_id';

	protected $fillable = [
		'sender_user_id',
		'message',
		'data',
		'type',
		'parent_id'
	];

	protected $table = 'notifications';


	public function add($message, $data = [], $type = 0, $parent_id = 0)
	{

		$user = \Auth::getUser();
		if(!$user)
			$user_id = 0;
		else
			$user_id = $user->user_id;

		$this->message = $message;
		$this->type = $type;
		$this->data = json_encode($data);
		$this->sender_user_id = $user_id;
		$this->parent_id = $parent_id;

		$this->save();

		return [
			'notification_id' => $this->notification_id,
			'created_at' => $this->created_at
		];

	}

	public function get( $device_id, $user_id = 0 )
	{

		$query = \DB::table($this->table)
			->select(
				$this->table . '.message',
				$this->table . '.type',
				$this->table . '.data',
				'notifications_relations.notification_rel_id',
				'notifications_relations.created_at',
				'notifications_relations.status'
			)
			->leftJoin('notifications_relations', function($join){
				$join->on('notifications_relations.notification_id', '=', $this->table . '.notification_id');
			});

		if($user_id)
			$query
				->where('notifications_relations.device_id', '=', $device_id)
				->where('notifications_relations.user_id', '=', $user_id)->orWhere('notifications_relations.user_id', '=', 0);
		else
			$query->where('notifications_relations.device_id', '=', $device_id)->where('notifications_relations.user_id', '=', 0);

		$query->groupBy('notifications_relations.notification_id');

		$query->orderBy('created_at', 'desc');

		$data = $query->paginate(30);

		$count = 0;

		if(!$data)
			return [];

		if($user_id)
			$count = NotificationsRelations::where('device_id', '=', $device_id)
				->where('status', '=', 1)
				->get();
		else
			$count = NotificationsRelations::where('device_id', '=', $device_id)
				->where('status', '=', 1)
				->where('user_id', '=', $user_id)
				->get();

		$return = [];

		foreach($data->items() as $result)
		{
			$return['data'][] = [
				'notification_id' => $result->notification_rel_id,
				'message' => $result->message,
				'status' => $result->status,
				'type' => $result->type,
				'created_at' => date('Y-m-d H:i:s', strtotime($result->created_at)),
				'data' => json_decode($result->data)
			];
		}
		$return['unreaded'] = count($count);

		$return['pagination'] = [
			'total' => $data->total(),
			'last_page' => $data->lastPage(),
			'next_page' => $data->nextPageUrl(),
			'prev_page' => $data->previousPageUrl(),
			'per_page' => $data->perPage(),
			'current_page' => $data->currentPage()
		];

		return $return;
	}

	public function getForAdmin()
	{
		$query = \DB::table($this->table)
		            ->select(
			            $this->table . '.message',
			            $this->table . '.type',
			            $this->table . '.data',
			            'notifications_relations.notification_rel_id',
			            'notifications_relations.created_at',
			            'notifications_relations.status'
		            )
		            ->leftJoin('notifications_relations', function($join){
			            $join->on('notifications_relations.notification_id', '=', $this->table . '.notification_id');
		            })
					->where('notifications_relations.user_id', '=', 0);

		$query->groupBy($this->table . '.notification_id');

		$data = $query->paginate(5);


		$return = [];

		foreach($data->items() as $result)
		{
			$return['data'][] = [
				'notification_id' => $result->notification_rel_id,
				'message' => $result->message,
				'status' => $result->status,
				'type' => $result->type,
				'created_at' => date('Y-m-d H:i:s', strtotime($result->created_at)),
				'data' => json_decode($result->data)
			];
		}

		$return['pagination'] = [
			'total' => $data->total(),
			'last_page' => $data->lastPage(),
			'next_page' => $data->nextPageUrl(),
			'prev_page' => $data->previousPageUrl(),
			'per_page' => $data->perPage(),
			'current_page' => $data->currentPage()
		];

		return $return;
	}



}
