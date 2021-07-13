<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Bookings;
use App\Models\BookingsPayments;
use App\Models\FlightPassengers;
use App\Models\Flights;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Aircrafts;

use Location\Coordinate;
use Location\Distance\Vincenty;

class BookingCharterController extends Controller
{
    public function get(Request $request)
    {
        $data = $request->only([
            'origin_lat',
            'origin_lon',
            'destination_lat',
            'destination_lon',
            'return'
        ]);


        $coordinate1 = new Coordinate($data['origin_lat'], $data['origin_lon']); // Mauna Kea Summit
        $coordinate2 = new Coordinate($data['destination_lat'], $data['destination_lon']); // Haleakala Summit

        $calculator = new Vincenty();

        $distance = $calculator->getDistance($coordinate1, $coordinate2) * 0.000621371192;


        $aircrafts_model = new Aircrafts();

        $aircrafts = $aircrafts_model->get();

        $return = [];

        foreach($aircrafts['data'] as $aircraft)
        {
            $price = round($aircraft['flight_price'] * $distance);
            if($data['return'])
                $price = $price * 2;
            $return[] = [
                'aircraft_id' => $aircraft['aircraft_id'],
                'price' => (string)$price,
                'name' => $aircraft['name'],
                'seats' => $aircraft['seats'],
                'image_path' => url($aircraft['image_path'] . $aircraft['image_name'])
            ];

        }

        return Response::success($return);

    }

    public function book( Request $request ) 
    {
        $data = $request->only([
            'origin_lat',
            'origin_lon',
            'origin_location',
            'destination_lat',
            'destination_lon',
            'destination_location',
            'aircraft_id',
            'return',
            'flight_date_start',
            'flight_date_end',
            'passengers_count'
        ]);

        $coordinate1 = new Coordinate($data['origin_lat'], $data['origin_lon']);
        $coordinate2 = new Coordinate($data['destination_lat'], $data['destination_lon']);

        $calculator = new Vincenty();

        $distance = $calculator->getDistance($coordinate1, $coordinate2) * 0.000621371192;

        $aircrafts_model = new Aircrafts();
        $aircraft = $aircrafts_model->getById($data['aircraft_id']);

        $data['aircraft_image_id'] = $aircraft['image_id'];
        $data['aircraft_seats'] = $aircraft['seats'];

        $data['price'] = round($distance * $aircraft['flight_price']);
        $price = $data['price'];
        if($data['return'])
            $data['total_price'] = $price * 2;
        else
            $data['total_price'] = $price;

        $user = \Auth::getUser();

        $flights_model = new Flights();
        $flight_id = $flights_model->addCustomFlight($data);

        $flight_ids = [];
        $flight_ids[] = $flight_id;
        if($data['return'])
        {
            $origin = [
                'origin_lat' => $data['origin_lat'],
                'origin_lon' => $data['origin_lon'],
                'origin_location' => $data['origin_location']
            ];
            $destination = [
                'destination_lat' => $data['destination_lat'],
                'destination_lon' => $data['destination_lon'],
                'destination_location' => $data['destination_location'],
            ];

            $flights_model = new Flights();
            $flight_id = $flights_model->addCustomFlight([
                'origin_lat' => $destination['destination_lat'],
                'origin_lat' => $destination['destination_lat'],
                'origin_lon' => $destination['destination_lon'],
                'origin_location' => $destination['destination_location'],
                'destination_lat' => $origin['origin_lat'],
                'destination_lon' => $origin['origin_lon'],
                'destination_location' => $origin['origin_location'],
                'flight_date_start' => $data['flight_date_end'],
                'price' => $price,
                'total_price' => $price * 2,
                'aircraft_id' => $data['aircraft_id'],
                'aircraft_image_id' => $data['aircraft_image_id'],
                'aircraft_seats' => $data['aircraft_seats']
            ]);
            $flight_ids[] = $flight_id;
        }

        $booking = new Bookings();
        $booking->customBooking($user->user_id, [
            'flight_ids' => $flight_ids,
            'price' => $data['price'],
            'total_price' => $data['total_price'],
            'return' => $data['return'],
            'flight_date_start' => $data['flight_date_start'],
            'flight_date_end' => $data['flight_date_end'],
            'passengers_count' => $data['passengers_count']
        ]);
	    

        return Response::success([
            'message' => 'Your request is submitted'
        ]);
    }

    public function finishBooking(Request $request)
    {

        $data = $request->only([
            'booking_id',
            'passangers',
            'payment_confirmation',
        ]);

        $booking = Bookings::where('booking_id', '=', $data['booking_id'])->first();

        $booking_payment = new BookingsPayments();
        $booking_payment_res = $booking_payment->addPayPal($booking->booking_id, $data['payment_confirmation']);
        $booking->update([
            'payment_id' => $booking_payment_res,
            'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING
        ]);

        $booking_return = Bookings::where( 'parent_id', '=', $booking->booking_id );
        $booking_return->update( [
            'payment_id' => $booking_payment_res,
            'status' => Bookings::BOOKING_STATUS_CUSTOM_PENDING
        ] );

        $flight_passengers = new FlightPassengers();
        $flight_passengers_res = $flight_passengers->addPassengers($booking->flight_id, $booking->booking_id, $data['passangers']);

        if($booking_return->first())
        {

            $booking_return = $booking_return->first();
            $flight_passengers = new FlightPassengers();
            $flight_passengers_res = $flight_passengers->addPassengers($booking_return->flight_id, $booking_return->booking_id, $data['passangers']);
        }

        return Response::success([
            'message' => 'Your flight is booked'
        ]);

    }


    public function finishBookingWeb(Request $request)
    {

        $data = $request->only([
            'booking_id',
            'passangers'
        ]);

        $booking = Bookings::where('booking_id', '=', $data['booking_id'])->first();

        $booking_return = Bookings::where( 'parent_id', '=', $booking->booking_id );


        $flight_passengers = new FlightPassengers();
        $flight_passengers_res = $flight_passengers->addPassengers($booking->flight_id, $booking->booking_id, $data['passangers']);

        if($booking_return->first())
        {

            $booking_return = $booking_return->first();

            $flight_passengers = new FlightPassengers();
            $flight_passengers_res = $flight_passengers->addPassengers($booking_return->flight_id, $booking_return->booking_id, $data['passangers']);
        }

        return Response::success([
            'message' => 'Your flight is booked'
        ]);

    }
}
