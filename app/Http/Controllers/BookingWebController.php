<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Response\Response;
use App\Models\BookingsPending;
use App\Models\Flights;
use App\Models\Payments;
use Illuminate\Http\Request;

use App\Http\Requests;
use PayPal\Api\Authorization;
use PayPal\Api\Payment;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class BookingWebController extends Controller
{
//    public function transaction( Request $request )
//    {
//        $user_id = \Auth::getUser()->user_id;
//
//        $data = $request->only([
//            'departure_end',
//            'departure_start',
//            'flight_id',
//            'passangers'
//        ]);
//
//        $apiContext = new ApiContext(new OAuthTokenCredential(
//            env( 'PAYPAL_CLIENT_ID' ),
//            env( 'PAYPAL_SECRET' )
//        ));
//       
//
//        $payment = Sale::get( '8RN388995K467753X', $apiContext );
//
//
//        $auth = Authorization::get( '8RN388995K467753X', $apiContext );
//
//
//        $amt = new \PayPal\Api\Amount();
//        $amt->setCurrency('USD');
//        $amt->setTotal(1500.00);
//
//        $capture = new \PayPal\Api\Capture();
//        $capture->setIsFinalCapture(true);
//        $capture->setAmount($amt);
//
//        $res = $auth->capture($capture, $apiContext);
//
//        echo '<pre>';
//        print_r($res);
//        die();
//
//    }
    public $emails = [];
    protected $auth;

    public function __construct(Guard $auth  )
    {
        $this->auth = $auth;
        if(env( 'CONTACT_EMAIL' ))
            $this->emails = explode(",", env( 'CONTACT_EMAIL' ));

    }
    public function sendEmail( Request $request) {
        $user = $this->auth->user();
        $data = $request->only([
            'aircraft_name',
            'price',
			'discount_price',
            'origin_location',
            'destination_location',
            'flight_start_human',
            'seats',
            'period_from',
            'period_to',
            'number_of_passengers'
        ]);
        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

        $html =
            "
                <p>
                    <b>Customer Name: {$user['first_name']} {$user['last_name']}</b>
                </p>
                <p>
                    <b>Customer Email: {$user['email']}</b>
                </p>
                <hr>
                <p>
                    Aircraft: {$data['aircraft_name']}
                </p>
                <p>
                    Price: {$data['discount_price']} USD
                </p>
                <p>
                    Departing From: {$data['origin_location']}
                </p>
                <p>
                    Arriving To: {$data['destination_location']}
                </p>
                <p>
                    Date: {$data['flight_start_human']}
                </p>
                <p>
                    Number of Seats: {$data['seats']}
                </p>
                <p><b>Number of Passengers: {$data['number_of_passengers']}</b></p>
                <p>
                    <b>When is the customer is available to fly?: From {$data['period_from']} To {$data['period_to']}</b>
                </p>
            ";

        $mail
            ->addTo($this->emails)
            ->addHeader('key', 'value')
            ->setFromName('Departure Club')
            ->setFrom('noreplay@thedepartureclub.com')
            ->setSubject('New Booking Request')
            ->setHtml($html);

        $response = $sendgrid->send($mail);


        return Response::success( [
            'message' => 'Message send, expect response soon'
        ] );
    }
    public function add( Request $request )
    {
        $data = $request->only([
            'flight_id',
            'departure_start',
            'departure_end',
            'passangers'
        ]);

        
        $valid = $this->validatePassengers( $data['passangers'] );

        if(!$valid)
            return Response::error([
                'message' => 'Please enter informations for passengers'
            ]);


        $flights_model = new Flights();

        $flight = $flights_model->getById( $data['flight_id'] );


        if(!$flight)
            return Response::error( [
                'message' => 'Error occurred please refresh browser and book flight again'
            ] );


        if($flight['status'] != Flights::FLIGHT_STATUS_ACTIVE)
            return Response::error( [
                'message' => 'Flight is not active'
            ] );


        if(count( $data['passangers'] ) > $flight['seats'])
            return Response::error( [
                'message' => 'You are exceeded maximum passengers for flight'
            ] );
        
        $booking_pending_id = BookingsPending::newBooking( $data['flight_id'], $data );

        return Response::success( [
            'pending_id' => $booking_pending_id,
            'price' => $flight['price']
        ] );


    }

    public function validatePassengers( $passengers )
    {

        $valid = TRUE;
        foreach ( $passengers as $passenger )
        {

            $v = \Validator::make($passenger, [
                'first_name' => 'required',
                'last_name' => 'required',
                'weight' => 'required',
                'birth_date' => 'required'
            ]);

            if($v->fails())
                $valid = FALSE;

        }
        return $valid;

    }
}
