<?php

namespace App\Http\Controllers\Admin;

use App\Http\Response\Response;
use App\Models\Airports;
use App\Models\Bookings;
use App\Models\Flights;
use App\Services\NotificationsService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use App\Models\Aircrafts;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookingsCharterController extends Controller
{

	public function get( Request $request )
	{
		$filer = $request->only(['from', 'to']);


		$filer['flight_type'] = Flights::FLIGHT_TYPE_CHARTER;

		$bookings = new Bookings();

		$bookings_data = $bookings->getBookings(FALSE, $filer, TRUE);

		return Response::success($bookings_data);

	}

	public function offer( Request $request )
	{
		$data = $request->only([
			'booking_id',
			'arriving_date',
			'arriving_time',
			'origin_airport',
			'destination_airport',
			'fbo',
			'final_price',
			'tail_number',
			'aircraft_id',
			'aircraft_image_id',
			'note',
			'return_fbo',
			'return_tail_number',
			'return_arriving_date',
			'return_arriving_time',
			'return_flight'
		]);
		$user = \Auth::getUser();


		$validate = $this->validateBooking($data);


		if($validate->fails())
		{
			return Response::error($validate->getMessageBag());
		}



		$booking = Bookings::where('booking_id', '=', $data['booking_id'])->first();


		$return_flight = FALSE;
		if($booking->price != $booking->total_price)
		{
			$return_flight = TRUE;
		}


		$arriving_date = date('Y-m-d', strtotime($data['arriving_date']));
		$arriving_time = date('H:i:s', strtotime($data['arriving_time']));
		$arriving_date_time = $arriving_date . ' ' . $arriving_time;
		$booking_update = $booking->update([
			'time_approved' => date('Y-m-d H:i:s'),
			'tail_number' => $data['tail_number'],
			'price' => $data['final_price'],
			'total_price' => $data['final_price'],
			'fbo' => $data['fbo'],
			'arriving_date_time' => $arriving_date_time,
			'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING_PAYMENT
		]);

		$origin = Airports::where('iata', '=', $data['origin_airport'])->first();
		unset($origin['airport_id']);
		unset($origin['active']);
		unset($origin['created_at']);
		unset($origin['updated_at']);
		
		$destination = Airports::where('iata', '=', $data['destination_airport'])->first();
		unset($destination['airport_id']);
		unset($destination['active']);
		unset($destination['created_at']);
		unset($destination['updated_at']);

		$flight = Flights::where('flight_id', '=', $booking->flight_id)->first();


		$flight->status = Flights::FLIGHT_STATUS_BOOKED;
		$flight->origin_location = $origin['location'];
		$flight->destination_location = $destination['location'];
		$flight->origin_lon = $origin['longitude'];
		$flight->origin_lat = $origin['latitude'];
		$flight->destination_lon = $destination['longitude'];
		$flight->destination_lat = $destination['latitude'];
		$flight->origin_airport_iata = $data['origin_airport'];
		$flight->destination_airport_iata = $data['destination_airport'];
		$flight->origin_airport_info = json_encode($origin);
		$flight->destination_airport_info = json_encode($destination);
		$flight->operator_id = $user->user_id;
		$flight->flight_end = $booking->arriving_date_time;
		$flight->aircraft_id = $data['aircraft_id'];
		$flight->aircraft_image_id = $data['aircraft_image_id'];
		$flight->save();

		if($return_flight)
		{
			$arriving_date = date('Y-m-d', strtotime($data['return_arriving_date']));
			$arriving_time = date('H:i:s', strtotime($data['return_arriving_time']));
			$return_arriving_date_time = $arriving_date . ' ' . $arriving_time;
			$return_booking = Bookings::where('parent_id', '=', $data['booking_id'])->first();
			$return_booking->update([
				'time_approved' => date('Y-m-d H:i:s'),
				'tail_number' => $data['return_tail_number'],
				'price' => $data['final_price'],
				'total_price' => $data['final_price'],
				'fbo' => $data['return_fbo'],
				'arriving_date_time' => $return_arriving_date_time,
				'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING_PAYMENT
			]);

			Flights::where('flight_id', '=', $return_booking->flight_id)->first()->update([
				'status' => Flights::FLIGHT_STATUS_BOOKED,
				'origin_location' => $destination['location'],
				'destination_location' => $origin['location'],
				'origin_lon' => $destination['longitude'],
				'origin_lat' => $destination['latitude'],
				'destination_lon' => $origin['longitude'],
				'destination_lat' => $origin['latitude'],
				'origin_airport_iata' => $data['destination_airport'],
				'destination_airport_iata' => $data['origin_airport'],
				'origin_airport_info' => json_encode($destination),
				'destination_airport_info' => json_encode($origin),
				'operator_id' => $user->user_id,
				'flight_end' => $return_booking->arriving_date_time,
				'aircraft_id' => $data['aircraft_id'],
				'aircraft_image_id' => $data['aircraft_image_id'],
			]);

		}





	}

	public function approve( Request $request )
	{

		$only = $request->only( ['booking_id'] );
		$booking_id = $only['booking_id'];

		$booking = Bookings::where( 'booking_id', '=', $booking_id )->first();

		$booking->update( [
			'status' => Bookings::BOOKING_STATUS_APPROVED
		] );

		Flights::where( 'flight_id', '=', $booking->flight_id )->first()->update( [
			'status' => Flights::FLIGHT_STATUS_BOOKED
		] );

		$paypal = new PayPalService();
		$paypal->charge($booking->booking_id);

		$notifications_service = new NotificationsService();

		$notifications_service->send($booking->user_id, 2, [
			'booking_id' => $booking->booking_id,
			'message' => 'Your flight is approved'
		], $booking->booking_id);

		$booking_return = Bookings::where( 'parent_id', '=', $booking_id )->first();

		if($booking_return)
		{
			$booking_return->update( [
				'status' => Bookings::BOOKING_STATUS_APPROVED
			] );
			Flights::where( 'flight_id', '=', $booking_return->flight_id )->first()->update( [
				'status' => Flights::FLIGHT_STATUS_BOOKED
			] );
		}





	}
	
	public function decline( Request $request )
	{

		$only = $request->only( ['booking_id'] );
		$booking_id = $only['booking_id'];

	}

	public function validateBooking( array $data )
	{

		\Validator::extend('aircraft', function($attribute, $value, $parameters)
		{

			if(Aircrafts::where('aircraft_id', '=', $value)->count())
				return TRUE;
			else
				return FALSE;

		});


		\Validator::extend('airport', function($attribute, $value, $parameters)
		{

			if(Airports::where('iata', '=', $value)->count())
				return TRUE;
			else
				return FALSE;

		});

		$validate = [
			'arriving_date' => 'required',
			'arriving_time' => 'required',
			'origin_airport' => 'required|airport',
			'destination_airport' => 'required|airport',
			'fbo' => 'required',
			'final_price' => 'required|numeric',
			'tail_number' => 'required',
			'aircraft_id' => 'required|aircraft'
		];

		if($data['return_flight'])
		{
			$validate['return_fbo'] = 'required';
			$validate['return_tail_number'] = 'required';
			$validate['return_arriving_date'] = 'required';
			$validate['return_arriving_time'] = 'required';
		}

		return \Validator::make($data, $validate, ['aircraft' => 'Please select valid aircraft']);



	}

}
