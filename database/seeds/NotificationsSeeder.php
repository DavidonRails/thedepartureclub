<?php

use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $installations = DB::table('installations')->groupBy('installations.device_id')->get();

        $bookings = DB::table('bookings')->get();
        $flights = DB::table('flights')->where('status', '=', 1)->get();

        $notifications_model = new \App\Models\Notifications();

        $notification = $notifications_model->add('Public subject 1', 'Public message 1');
        foreach($installations as $installation)
        {


            \App\Models\NotificationsRelations::create([
                'notification_id' => $notification['notification_id'],
                'device_id' => $installation->device_id,
                'user_id' => 0
            ]);



        }

        for ($i = 1; $i <= 3; $i++) {

            $flight = $flights[ $i ];

            $notifications_model = new \App\Models\Notifications();
            $notification = $notifications_model->add( 'Flight subject ' . (string)($i), 'Flight message ' . (string)($i), [
                'flight_id'   => $flight->flight_id,
                'origin'      => $flight->origin_location,
                'destination' => $flight->destination_location
            ], 1 );

            $installations = DB::table('installations')->where('user_id', '=', 3)->groupBy('installations.device_id')->get();


            foreach ( $installations as $installation ) {


                \App\Models\NotificationsRelations::create( [
                    'notification_id' => $notification['notification_id'],
                    'device_id'       => $installation->device_id,
                    'user_id'         => $installation->user_id
                ] );


            }

        }


        for ($i = 1; $i <= 3; $i++) {

            $booking       = $bookings[ $i ];

            $flight_model = new \App\Models\Flights();

            $flight_data = $flight_model->getById($booking->flight_id);

            $notifications_model = new \App\Models\Notifications();
            $notification = $notifications_model->add( 'Booking subject ' . (string)($i), 'Booking message ' . (string)($i), [
                'booking_id'   => $booking->booking_id,
                'flight_id'      => $booking->flight_id,
                'price' => $booking->price,
                'origin'      => $flight_data['origin_location'],
                'destination' => $flight_data['destination_location']
            ], 2 );

            $installations = DB::table('installations')->where('user_id', '=', 3)->groupBy('installations.device_id')->get();


            foreach ( $installations as $installation ) {


                \App\Models\NotificationsRelations::create( [
                    'notification_id' => $notification['notification_id'],
                    'device_id'       => $installation->device_id,
                    'user_id'         => $installation->user_id
                ] );


            }

        }


        $notifications_model = new \App\Models\Notifications();

        $notification = $notifications_model->add('Public subject 2', 'Public message 2');
        foreach($installations as $installation)
        {


            \App\Models\NotificationsRelations::create([
                'notification_id' => $notification['notification_id'],
                'device_id' => $installation->device_id,
                'user_id' => 0
            ]);



        }



    }
}
