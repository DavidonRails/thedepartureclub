<?php

use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $flights = DB::table('flights')->where('status', '=', 1)->get();

        $user = DB::table('users')->where('email', '=', 'admin@hobojet.com')->get();

        $user = $user[0];

        for ($i = 1; $i <= 5; $i++) {

            $flight = $flights[$i - 1];

            $date = strtotime('NOW + 7 days');

            $book_data = [
                'flight_id' => $flight->flight_id,
                'departure_start' => date('H:i', $date),
                'departure_end' => date('H:i', strtotime(date('H:i', $date) . ' + ' . rand(0, 6) . ' HOURS')),
                'price' => $flight->price,
                'passengers_count' => 1
            ];


            $bookings = new \App\Models\Bookings();
            $booking_id = $bookings->book($user->user_id, $book_data);


            $flight_passengers = new \App\Models\FlightPassengers();
            $flight_passengers->addPassengers($flight->flight_id, $booking_id, json_encode([
                [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'birth_date' => date('Y-m-d', strtotime('NOW - ' . rand(25, 50) . ' YEARS - ' . rand(0, 350) . ' DAYS')),
                    'weight' => rand(80, 150)
                ]
            ]));







        }


    }
}
