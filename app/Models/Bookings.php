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
class Bookings extends Model
{

	protected $table = 'bookings';

	protected $primaryKey = 'booking_id';


	const BOOKING_STATUS_PENDING = 0;
	const BOOKING_STATUS_APPROVED = 1;
	const BOOKING_STATUS_REJECTED = 2;
	const BOOKING_STATUS_CUSTOM_PENDING_OFFER = 3;
	const BOOKING_STATUS_CUSTOM_PENDING_PAYMENT = 4;
	const BOOKING_STATUS_CUSTOM_PENDING = 5;

	protected $fillable = ['user_id', 'status', 'time_approved', 'tail_number', 'price', 'payment_id', 'fbo', 'passengers_count', 'arriving_date_time', 'parent_id', 'total_price'];


	public function book( $user_id, $data )
	{

		$this->user_id = $user_id;
		$this->flight_id = $data['flight_id'];
		$this->status = self::BOOKING_STATUS_PENDING;
		$this->price = $data['price'];
		$this->time_requested = date('Y-m-d H:i:s');
		$this->departure_time_start = $data['departure_start'];
		$this->departure_time_end = $data['departure_end'];
		$this->passengers_count = $data['passengers_count'];

		$book = $this->save();

		if($book)
			return $this->booking_id;


		return FALSE;

	}

	public function customBooking( $user_id, $data ) 
	{

		$data['parent_id'] = 0;
		$data['flight_date'] = $data['flight_date_start'];
		$data['flight_time'] = $data['flight_date_start'];
		$data['flight_id'] = $data['flight_ids'][0];

		$data['status'] = self::BOOKING_STATUS_CUSTOM_PENDING_OFFER;
		$booking_id = $this->_customBookingAdd($user_id, $data);

		if($data['return'])
		{
			$data['flight_date'] = $data['flight_date_end'];
			$data['flight_time'] = $data['flight_date_end'];
			$data['flight_id'] = $data['flight_ids'][1];
			$data['parent_id'] = $booking_id;

			$data['status'] = self::BOOKING_STATUS_CUSTOM_PENDING_OFFER;

			$this->_customBookingAdd($user_id, $data);

		}

	}

	public function _customBookingAdd($user_id, $data)
	{

		$bookings = new Bookings();
		$bookings->user_id = $user_id;
		$bookings->flight_id = $data['flight_id'];
		$bookings->status = $data['status'];
		$bookings->price = $data['price'];
		$bookings->total_price = $data['total_price'];
		$bookings->time_requested = date('Y-m-d H:i:s');
		$bookings->departure_time_start = date('H:i', 0);
		$bookings->departure_time_end = date('H:i', 0);
		$bookings->departure_time_final = date('H:i', strtotime($data['flight_time']));
		$bookings->passengers_count = $data['passengers_count'];
		$bookings->parent_id = $data['parent_id'];
		$bookings->return_flight = $data['return'];

		$book = $bookings->save();
		if($book)
			return $bookings->booking_id;


		return FALSE;
	}


	public function getBookings($user_id = NULL, $filter = [], $ajax = FALSE)
	{


		$query = \DB::table($this->table)->select(
			$this->table . '.*',
			$this->table . '.status AS booking_status',
			$this->table . '.price AS booking_price',
			'flights.*',
			'aircrafts.name AS aircraft_name',
			'aircrafts.seats AS aircraft_seats',
			'aircrafts_images.name AS image_name',
			'aircrafts_images.path AS image_path'
		)
			->leftJoin('flights', function($join){
				$join->on('bookings.flight_id', '=', 'flights.flight_id');
			})
			->leftJoin('aircrafts', function($join){
				$join->on('flights.aircraft_id', '=', 'aircrafts.aircraft_id');
			})
			->leftJoin('aircrafts_images', function($join){
				$join->on('flights.aircraft_image_id', '=', 'aircrafts_images.image_id');
			});

		if(!empty($filter['from']) && !empty($filter['to']))
		{
			$query->whereBetween('flights.flight_start', [new \DateTime($filter['from']), new \DateTime($filter['to'])]);
		}

		$user = \Auth::getUser();
		if(isset($user) && $user->role == 'operator' && isset($filter['flight_type']) && $filter['flight_type'] == Flights::FLIGHT_TYPE_DEAL)
		{
			$query->where('flights.operator_id', '=', $user->user_id);
		}
		if(isset($filter['flight_type']) && $filter['flight_type'] == Flights::FLIGHT_TYPE_CHARTER)
		{
			$query->whereIn('flights.operator_id', [$user->user_id, 0]);
		}

		if($user_id)
			$query->where($this->table . '.user_id', '=', $user_id);

		if(isset($filter['flight_type']))
			$query->where('flights.type', '=', $filter['flight_type']);

		$query->orderBy('booking_status', 'desc');
		$query->orderBy('booking_id', 'desc');



		$bookings = $query->paginate(20);

		if(!$bookings)
			return FALSE;

		$return = [
			'data' => [],
			'pagination' => []
		];


		foreach($bookings->items() as $key => $booking)
		{

			if(in_array( $booking->booking_status, [self::BOOKING_STATUS_CUSTOM_PENDING_OFFER, self::BOOKING_STATUS_CUSTOM_PENDING_PAYMENT, self::BOOKING_STATUS_CUSTOM_PENDING] ) && $booking->parent_id > 0)
				continue;


			if(!$ajax)
				$image = url('') . '/' . $booking->image_path . $booking->image_name;
			else
				$image = $booking->image_path . $booking->image_name;

			if($booking->type == Flights::FLIGHT_TYPE_CHARTER)
				$flight_price = $booking->total_price;
			else
				$flight_price = $booking->booking_price;

			$flight_paid = 0;
			if($booking->payment_id == 0)
				$flight_paid = 0;
			else
				$flight_paid = 1;

			if($booking->booking_status == self::BOOKING_STATUS_CUSTOM_PENDING_OFFER)
				$flight_paid = 2;

			$return['data'][] = [
				'booking_id' => $booking->booking_id,
				'status' => $this->getStatus($booking->booking_status),
				'departure_time_start' => $booking->departure_time_start,
				'departure_time_end' => $booking->departure_time_end,
				'departure_time_final' => $booking->departure_time_final,
				'departure_date' => date('Y-m-d', strtotime($booking->flight_start)),
				'flight_id' => $booking->flight_id,
				'flight_origin' => $booking->origin_location,
				'flight_destination' => $booking->destination_location,
				'flight_price' => $flight_price,
				'flight_price_total' => $booking->total_price,
				'aircraft_name' => $booking->aircraft_name,
				'aircraft_seats' => $booking->aircraft_seats,
				'aircraft_image' => $image,
				'flight_type' => $booking->type,
				'flight_paid' => $flight_paid,
				'return' => ($booking->return_flight) ? 1 : 0,
				'until_takeoff' => round((strtotime($booking->flight_start) - strtotime('NOW')) / 86400),
				'departure_date_human' => date('M j, Y', strtotime($booking->flight_start))
			];


		}

		if($ajax)
			$return['pagination'] = [
				'total' => $bookings->total(),
				'last_page' => $bookings->lastPage(),
				'next_page' => str_replace(url('api') . '/', '', $bookings->nextPageUrl()),
				'prev_page' => str_replace(url('api') . '/', '', $bookings->previousPageUrl()),
				'per_page' => $bookings->perPage(),
				'current_page' => $bookings->currentPage()
			];
		else
			$return['pagination'] = [
				'total' => $bookings->total(),
				'last_page' => $bookings->lastPage(),
				'next_page' => $bookings->nextPageUrl(),
				'prev_page' => $bookings->previousPageUrl(),
				'per_page' => $bookings->perPage(),
				'current_page' => $bookings->currentPage()
			];

		return $return;
	}

	public function getById( $booking_id, $ajax = FALSE )
	{

		$booking = \DB::table($this->table)
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
			->leftJoin('flights', function($join){
				$join->on('bookings.flight_id', '=', 'flights.flight_id');
			})
			->leftJoin('aircrafts', function($join){
				$join->on('flights.aircraft_id', '=', 'aircrafts.aircraft_id');
			})
			->leftJoin('aircrafts_images', function($join){
				$join->on('flights.aircraft_image_id', '=', 'aircrafts_images.image_id');
			})
			->where('booking_id', '=', $booking_id)
			->get();



		if(!$booking)
			return FALSE;


		$flight_passengers = new FlightPassengers();

		$flight_passengers_res = $flight_passengers->getByBookingId($booking_id);

		$booking = $booking[0];
		
		if(!$ajax)
			$image = url('') . '/' . $booking->image_path . $booking->image_name;
		else
			$image = $booking->image_path . $booking->image_name;

		$booked_by_data = User::getById($booking->user_id);

		$booked_by = [
			'user_id' => $booked_by_data->user_id,
			'first_name' => $booked_by_data->first_name,
			'last_name' => $booked_by_data->last_name,
			'email' => $booked_by_data->email
		];

		$return_booking_info = [];
		$return_booking = Bookings::where( 'parent_id', '=', $booking->booking_id )->first();
		if($return_booking)
		{
			$return_booking = $this->getById( $return_booking->booking_id );
			$return_booking_info = [
				'departure_date_time' => $return_booking['departure_date_time'],
			];
		}

		$flight_paid = 0;
		if($booking->payment_id == 0)
			$flight_paid = 0;
		else
			$flight_paid = 1;

		if($booking->status == self::BOOKING_STATUS_CUSTOM_PENDING_OFFER)
			$flight_paid = 2;

		$return = [
			'booking_id' => $booking->booking_id,
			'status' => $this->getStatus($booking->status),
			'departure_time_start' => $booking->departure_time_start,
			'departure_time_end' => $booking->departure_time_end,
			'departure_time_final' => $booking->departure_time_final,
			'departure_date' => date('Y-m-d', strtotime($booking->flight_start)),
			'departure_date_time' => date('Y-m-d H:i', strtotime(date('Y-m-d', strtotime($booking->flight_start)) . ' ' . $booking->departure_time_final . ':00')),
			'arriving_date_time' => date('Y-m-d H:i', strtotime($booking->arriving_date_time)),
			'flight_id' => $booking->flight_id,
			'flight_origin' => $booking->origin_location,
			'flight_origin_airport_info' => json_decode($booking->origin_airport_info, TRUE),
			'flight_destination' => $booking->destination_location,
			'flight_destination_airport_info' => json_decode($booking->destination_airport_info, TRUE),
			'flight_price' => $booking->price,
			'flight_price_total' => $booking->total_price,
			'aircraft_name' => $booking->aircraft_name,
			'aircraft_seats' => $booking->aircraft_seats,
			'aircraft_image' => $image,
			'flight_passengers' => $flight_passengers_res,
			'flight_passengers_count' => $booking->passengers_count,
			'booked_by' => $booked_by,
			'fbo' => $booking->fbo,
			'tail_number' => $booking->tail_number,
			'flight_type' => $booking->type,
			'flight_paid' => $flight_paid,
			'return' => ($booking->return_flight) ? 1 : 0,
			'return_flight' => $return_booking_info,
			'until_takeoff' => round((strtotime($booking->flight_start) - strtotime('NOW')) / 86400),
			'departure_date_human' => date('M j, Y', strtotime($booking->flight_start))
		];



		return $return;
	}


	public function getStatus( $status )
	{
		switch ($status)
		{

			case self::BOOKING_STATUS_PENDING:
			case self::BOOKING_STATUS_CUSTOM_PENDING:
				return 'Pending';
				break;
			case self::BOOKING_STATUS_APPROVED;
				return 'Approved';
				break;
			case self::BOOKING_STATUS_REJECTED:
				return 'Rejected';
				break;
			case self::BOOKING_STATUS_CUSTOM_PENDING_OFFER:
				return 'Waiting for approval';
				break;
			case self::BOOKING_STATUS_CUSTOM_PENDING_PAYMENT:
				return 'Waiting for payment';
				break;
			default:
				return 'Unknown';
				break;

		}
	}

	public function approve( $data )
	{

		Bookings::where('booking_id', '=', $data['booking_id'])
			->update(
				[
					'arriving_date_time' => date('Y-m-d H:i:s', strtotime($data['arriving_date'] . ' ' . $data['arriving_time'])),
					'departure_time_final' => $data['departure_time_final'],
					'tail_number' => $data['tail_number'],
					'fbo' => $data['fbo'],
					'status' => self::BOOKING_STATUS_APPROVED
				]
			);
		
		Flights::where('flight_id', '=', $data['flight_id'])
			->update(
				[
					'status' => Flights::FLIGHT_STATUS_BOOKED
				]
			);


		$booking = Bookings::where('booking_id', '=', $data['booking_id'])->get();

		if(!$booking)
			return FALSE;

		$booking = $booking[0];


		$paypal = new PayPalService();
		$paypal->charge($booking->booking_id);
		
		$notifications_service = new NotificationsService();

		$notifications_service->send($booking->user_id, 2, [
			'booking_id' => $booking->booking_id,
			'message' => 'Your flight is approved'
		], $booking->booking_id);



		return TRUE;

	}

	public function decline($data)
	{
		Bookings::where('booking_id', '=', $data['booking_id'])
		        ->update(
			        [
				        'status' => self::BOOKING_STATUS_REJECTED
			        ]
		        );

		$booking = Bookings::where('booking_id', '=', $data['booking_id'])->first();

		Flights::where('flight_id', '=', $booking->flight_id)
		       ->update(
			       [
				       'status' => Flights::FLIGHT_STATUS_CANCELED
			       ]
		       );



		if(!$booking)
			return FALSE;


		$paypal = new PayPalService();
		$paypal->void($booking->booking_id);
		

		$notifications_service = new NotificationsService();
		
		$notifications_service->send($booking->user_id, 2, [
			'booking_id' => $booking->booking_id,
			'message' => $data['note']
		], $booking->booking_id);

		if($booking != FALSE)
			return TRUE;
		else
			return FALSE;
	}

}
