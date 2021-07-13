<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Bookings;
use App\Models\BookingsPayments;
use App\Models\FlightPassengers;
use App\Models\Flights;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class BookingsController extends Controller
{

	public function add(Request $request)
	{

		$user_id = \Auth::getUser()->user_id;


		$data = $request->only([
			'departure_start',
			'departure_end',
			'flight_id',
			'passangers',
			'payment_confirmation'
		]);


		$flights = new Flights();

		$flight = $flights->getById($data['flight_id']);

		if(!$flight)
			return Response::error([
				'Please contact support'
			]);

		$book_data = [
			'flight_id' => $data['flight_id'],
			'departure_start' => $data['departure_start'],
			'departure_end' => $data['departure_end'],
			'price' => $flight['price'],
			'passengers_count' => count($data['passangers'])
		];


		$bookings = new Bookings();
		$booking_id = $bookings->book($user_id, $book_data);

		$booking_payment = new BookingsPayments();
		$booking_payment_res = $booking_payment->addPayPal($booking_id, $data['payment_confirmation']);

		Bookings::where('booking_id', '=', $booking_id)->update([
			'payment_id' => $booking_payment_res
		]);

		$flight_passengers = new FlightPassengers();
		$flight_passengers_res = $flight_passengers->addPassengers($data['flight_id'], $booking_id, $data['passangers']);

		if(!$booking_id || !$booking_payment_res ||  !$flight_passengers_res)
			return Response::error([
				'Please contact support'
			]);

		return Response::success([
			'Booking successful'
		]);

	}



	public function getAll( )
	{

		$user_id = \Auth::getUser()->user_id;

		$bookings = new Bookings();

		$bookings_all = $bookings->getBookings($user_id);

		return Response::success($bookings_all);

	}

	public function get( Request $request )
	{

		$data = $request->only([
			'booking_id'
		]);

		$bookings = new Bookings();

		$booking_details = $bookings->getById($data['booking_id']);

		return Response::success($booking_details);

	}


}
