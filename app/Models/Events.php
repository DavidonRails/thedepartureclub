<?php

namespace App\Models;

use App\Services\Notifications;
use App\Services\NotificationsService;
use App\Services\PayPalService;
use Illuminate\Database\Eloquent\Model;

/**
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Events extends Model
{

	protected $table = 'events';

	protected $primaryKey = 'id';

	
	protected $fillable = [
		'name',
		'description',
		'location',
		'seats',
		//'event_image',
		'event_at',
		'event_ends_at',
		'created_at',
		'updated_at'
	];


	// public function getEvents($filter = [], $ajax = FALSE)
	// {
	// 	$query = \DB::table($this->table)->select(
	// 		$this->table . '.*',
	// 		'events.*',
	// 	)
	// 	->leftJoin('events_images', function($join){
	// 		$join->on('events.event_image_id', '=', 'events_images.image_id');
	// 	})->get();
	// }

	public function getById( $event_id, $ajax = FALSE )
	{

		$event = \DB::table($this->table)
			->select(
			$this->table . '.*',
				'flights.flight_id',
				'flights.type',
				'flights.flight_start',
				'flights.origin_location',
				'flights.origin_airport_info',
				'flights.destination_location',
				'flights.destination_airport_info',
				'aircrafts.name AS aircraft_name',
				'aircrafts.seats AS aircraft_seats',
				'aircrafts_images.name AS image_name',
				'aircrafts_images.path AS image_path'
			)
			->where('event_id', '=', $event_id)
			->get();



		if(!$event)
			return FALSE;

		$return = [
			'event_id' => $event->event_id,
			'status' => $this->getStatus($event->status),
			'departure_time_start' => $event->departure_time_start,
			'departure_time_end' => $event->departure_time_end,
			'departure_time_final' => $event->departure_time_final,
			'departure_date' => date('Y-m-d', strtotime($event->flight_start)),
			'departure_date_time' => date('Y-m-d H:i', strtotime(date('Y-m-d', strtotime($event->flight_start)) . ' ' . $event->departure_time_final . ':00')),
			'arriving_date_time' => date('Y-m-d H:i', strtotime($event->arriving_date_time)),
			'flight_id' => $event->flight_id,
			'flight_origin' => $event->origin_location,
			'flight_origin_airport_info' => json_decode($event->origin_airport_info, TRUE),
			'flight_destination' => $event->destination_location,
			'flight_destination_airport_info' => json_decode($event->destination_airport_info, TRUE),
			'flight_price' => $event->price,
			'flight_price_total' => $event->total_price,
			'aircraft_name' => $event->aircraft_name,
			'aircraft_seats' => $event->aircraft_seats,
			'aircraft_image' => $image,
			'flight_passengers' => $flight_passengers_res,
			'flight_passengers_count' => $event->passengers_count,
			'booked_by' => $booked_by,
			'fbo' => $event->fbo,
			'tail_number' => $event->tail_number,
			'flight_type' => $event->type,
			'flight_paid' => $flight_paid,
			'return' => ($event->return_flight) ? 1 : 0,
			'return_flight' => $return_event_info,
			'until_takeoff' => round((strtotime($event->flight_start) - strtotime('NOW')) / 86400),
			'departure_date_human' => date('M j, Y', strtotime($event->flight_start))
		];



		return $return;
	}



}
