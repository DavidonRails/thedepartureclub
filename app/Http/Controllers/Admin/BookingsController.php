<?php

namespace App\Http\Controllers\Admin;

use App\Http\Response\Response;
use App\Jobs\Mail;
use App\Models\Flights;
use App\Models\Notes;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Http\Controllers\Controller;

class BookingsController extends Controller
{

    public function get( Request $request)
    {

        $filer = $request->only(['from', 'to']);


        $filer['flight_type'] = Flights::FLIGHT_TYPE_DEAL;

        $bookings = new Bookings();

        $bookings_data = $bookings->getBookings(FALSE, $filer, TRUE);

        return Response::success($bookings_data);



    }

    public function details( Request $request )
    {
        $data = $request->only(['booking_id']);

        $bookings = new Bookings();

        $bookings_data = $bookings->getById($data['booking_id'], TRUE);

        return Response::success($bookings_data);
    }


    public function accept( Request $request )
    {


        $data = $request->only([
            'booking_id',
            'flight_id',
            'arriving_date',
            'arriving_time',
            'departure_time_final',
            'tail_number',
            'fbo',
            'note'
        ]);


        $bookings = new Bookings();

        $book = $bookings->approve($data);

        Notes::add($data['note'], Notes::NOTE_APPROVED, $data['booking_id']);


        $booking = $bookings->getById($data['booking_id']);
        
        $user = User::getById($booking['booked_by']['user_id']);

        $this->dispatch(new Mail([
            'type' => Mail::ACCEPTED_FLIGHT,
            'data' => [
                'destination' => $booking['flight_destination'],
                'date' => $booking['departure_date'],
                'time' => $booking['departure_date_time']
            ],
            'subject' => $booking['flight_destination'],
            'user' => [
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ]
           
        ]));
        


        if($book)
            return Response::success([
                'message' => 'Booking successful'
            ]);
        else
            return Response::error([
                'message' => 'Please contact support'
            ]);


    }

    public function decline( Request $request ) {

        $data = $request->only([
            'booking_id',
            'note'
        ]);

        if(strlen($data['note']) < 6)
            return Response::error([
                'message' => 'Please enter note'
            ]);


        $bookings = new Bookings();

        $book = $bookings->decline($data);

        Notes::add($data['note'], Notes::NOTE_DECLINED, $data['booking_id']);

        $booking = $bookings->getById($data['booking_id']);

        $user = User::getById($booking['booked_by']['user_id']);


        $this->dispatch(new Mail([
            'type' => Mail::DECLINED_FLIGHT,
            'data' => [],
            'user' => [
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ]
        ]));
        


        if($book)
            return Response::success([
                'message' => 'Booking declined'
            ]);
        else
            return Response::error([
                'message' => 'Please contact support'
            ]);

    }


    public function validateAccept($data)
    {
        return \Validator::make($data, [
            'booking_id' => 'required',
            'arriving_date' => 'required',
            'arriving_time' => 'required',
            'departure_time_final' => 'required',
            'tail_number' => 'required',
            'fbo' => 'required'
        ]);
    }

}
