<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\BillingPending;
use App\Models\Bookings;
use App\Models\BookingsPayments;
use App\Models\BookingsPending;
use App\Models\FlightPassengers;
use App\Models\Flights;

class IpnService
{

	const TYPE_SUBSRC_SIGNUP = 'subscr_signup';
	const TYPE_SUBSRC_CANCEL = 'subscr_cancel';
	const TYPE_SUBSRC_FAILED = 'subscr_failed';
	const TYPE_WEB_ACCEPT = 'web_accept';

	public $ipn_response = [];

	public $type = NULL;

	public function __construct($ipn_response)
	{


		$this->ipn_response = $ipn_response;

		if(!isset($this->ipn_response['txn_type']))
			return NULL;

		switch ($this->ipn_response['txn_type'])
		{
			case self::TYPE_SUBSRC_SIGNUP:
				$this->subscribeSubsrc();
				break;
			case self::TYPE_SUBSRC_CANCEL:
				$this->cancelSubsrc();
				break;
			case self::TYPE_WEB_ACCEPT:
				$this->bookFlight();
				break;
		}

	}

	public function subscribeSubsrc()
	{

		$data = explode( ':', $this->ipn_response['custom'] );

		$type = $data[0];
		$history_id = $data[1];

		$pending_data = Billing::where('history_id', '=', $history_id)->first();

		if(!$pending_data)
		{
			\Log::useDailyFiles(storage_path('logs/ipn-pp-processing.log'));
			\Log::info(
				'#### PAYPAL IPN PROCESSING ####' . "\n" .
				'- NO PENDING BILLING ID' . "\n" .
				'- IPN DATA' . "\n",
				$this->ipn_response
			);

			return FALSE;
		}

		Billing::activatePending( $pending_data->user_id, $this->ipn_response['subscr_id'] );

	}


	public function cancelSubsrc() 
	{
		$data = explode( ':', $this->ipn_response['custom'] );

		$type = $data[0];
		$history_id = $data[1];

		$pending_data = Billing::where('history_id', '=', $history_id)->first();

		if(!$pending_data)
		{
			\Log::useDailyFiles(storage_path('logs/ipn-pp-processing-error.log'));
			\Log::info(
				'#### PAYPAL IPN PROCESSING ####' . "\n" .
				'- NO PENDING BILLING ID' . "\n" .
				'- IPN DATA' . "\n",
				(array)$this->ipn_response
			);

			return FALSE;
		}

		Billing::cancelSubscription( $history_id );


	}
	
	
	public function bookFlight()
	{
		$data = explode( ':', $this->ipn_response['custom'] );

		$type = $data[0];



		switch ($type)
		{

			case 'b':

				$pending_id = $data[1];

				$pending_booking = BookingsPending::getById($pending_id);

				if(!$pending_booking)
					return NULL;

				$flights_model = new Flights();
				$flight = $flights_model->getById( $pending_booking->flight_id );

				if($flight['status'] != Flights::FLIGHT_STATUS_ACTIVE)
				{
					\Log::useDailyFiles(storage_path('logs/booking-flight-booked.log'));
					\Log::info('pending_id:' . $pending_id . '|flight_id:' . $pending_booking->flight_id);
					return NULL;
				}


				$bookings = new Bookings();
				$book_data = [
					'flight_id' => $pending_booking->flight_id,
					'price' => $flight['price'],
					'departure_start' => $pending_booking->departure_start,
					'departure_end' => $pending_booking->departure_end,
					'passengers_count' => count($pending_booking->passengers)
				];
				$booking_id = $bookings->book($pending_booking->user_id, $book_data);


				$booking_payment = new BookingsPayments();
				$booking_payment_res = $booking_payment->addPayPal($booking_id, $this->ipn_response);

				Bookings::where('booking_id', '=', $booking_id)->update([
					'payment_id' => $booking_payment_res
				]);


				$flight_passengers = new FlightPassengers();
				$flight_passengers_res = $flight_passengers->addPassengers($pending_booking->flight_id, $booking_id, $pending_booking->passengers);


				BookingsPending::bookingProcessed( $pending_id );
				break;

			case 'cb':

				$booking_id = $data[1];

				$booking = Bookings::where('booking_id', '=', $booking_id)->first();

				if(!$booking)
					return NULL;

				$booking_payment = new BookingsPayments();
		        $booking_payment_res = $booking_payment->addPayPal($booking->booking_id, $this->ipn_response);

		        $booking->update([
		            'payment_id' => $booking_payment_res,
		            'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING
		        ]);

				$booking_return = Bookings::where( 'parent_id', '=', $booking->booking_id );
				if($booking_return->first())
				{
			        $booking_return->update( [
				        'payment_id' => $booking_payment_res,
				        'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING
				    ] );
				}

				break;

		}




	}

}