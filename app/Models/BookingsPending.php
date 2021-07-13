<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingsPending extends Model
{

	const STATUS_PENDING = 1;
	const STATUS_DONE = 0;
	const STATUS_INVALID = 2;

	protected $table = 'bookings_pending';

	protected $primaryKey = 'booking_pending_id';

	protected $fillable = ['user_id', 'flight_id', 'status', 'departure_start', 'departure_end', 'passengers'];

	public static function newBooking($flight_id, $data)
	{

		$user_id = \Auth::getUser()->user_id;
		
		self::_clearDuplicates( $user_id, $flight_id );

		$booking = BookingsPending::create([
			'user_id' => $user_id,
			'flight_id' => $flight_id,
			'status' => self::STATUS_PENDING,
			'departure_start' => date('Y-m-d H:i:s', strtotime( $data['departure_start'] )),
			'departure_end' => date('Y-m-d H:i:s', strtotime( $data['departure_end'] )),
			'passengers' => json_encode( $data['passangers'] )
		]);


		return $booking->booking_pending_id;
		

	}

	public static function getById( $booking_pending_id )
	{

		return BookingsPending::where('booking_pending_id', '=', $booking_pending_id)->where('status', '=', self::STATUS_PENDING)->first();

	}

	public static function bookingProcessed($booking_pending_id)
	{
		BookingsPending::where('booking_pending_id', '=', $booking_pending_id)->first()->update([
			'status' => self::STATUS_DONE
		]);
	}

	private static function _clearDuplicates($user_id, $flight_id)
	{


		BookingsPending::where('flight_id', '=', $flight_id)->where('user_id', '=', $user_id)->update([
			'status' => self::STATUS_INVALID
		]);

	}

	
}
