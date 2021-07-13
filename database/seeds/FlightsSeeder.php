<?php

use Illuminate\Database\Seeder;

class FlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        Artisan::call( 'db:seed', [ '--class' => 'AircraftsSeeder' ] );

        $locations = [
            'IFP',
            'AZA',
            'EKO',
            'CLD',
            'MRY',
            'SMF',
            'SBA',
            'VGT',
            'LAS',
            'RNO'
        ];


        for ($i = 1; $i <= 10; $i++) {

            $or = rand(0, 4);
            $dr = rand(0, 4);
            while($or == $dr){
                $dr = rand(0, 9);
            }

            $lo = $this->location($locations[$or]);

            if(!isset($lo['location']))
                continue;

            unset($lo['link']);
            unset($lo['status']);

            $ld = $this->location($locations[$dr]);

            if(!isset($ld['location']))
                continue;
            
            unset($ld['link']);
            unset($ld['status']);

            $time = rand(1, 3);
            $date_start = date('Y-m-d h:i:s', strtotime(date('Y-m-d', strtotime('NOW + 3days')) . rand(0, 24) . ':' . rand(1, 59) . ' + ' . rand(2,10) . ' DAYS '));
            $date_end = date('Y-m-d h:i:s', strtotime($date_start . ' + ' . rand(1,2) . ' DAYS + ' . rand(50, 500) . ' minutes'));

            DB::table('flights')->insert([
                'flight_identification' => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                'hash' => md5($i.$date_start.$date_end),
                'flight_start' => $date_start,
                'flight_end' => $date_end,
                'aircraft_id' => 11,
                'aircraft_image_id' =>  3,
                'price' => rand(600, 2000),
                'seats' => rand(1, 8),
                'flight_time' => $time,


                'origin_location' => $lo['location'],
                'origin_lon' => $lo['longitude'],
                'origin_lat' => $lo['latitude'],
                'origin_airport_iata' => $lo['iata'],
                'origin_airport_info' => json_encode($lo),

                'destination_location' => $ld['location'],
                'destination_lon' => $ld['longitude'],
                'destination_lat' => $ld['latitude'],
                'destination_airport_iata' => $ld['iata'],
                'destination_airport_info' => json_encode($ld),
                'operator_id' => 27
            ]);


        }

    }

    public function location( $string )
    {

        $airports = new \App\Models\Airports();

        $airport = $airports->searchIata($string);

        if($airport)
            return $airport;
        else
            return $this->searchApi($string);



    }

    public function searchApi( $string )
    {
        $airport = $this->http('http://www.airport-data.com/api/ap_info.json?iata=' . $string);

        if($airport['status'] == 200 && $airport['latitude'] && $airport['longitude'])
        {
            $airports = new \App\Models\Airports();
            $airports->addNew($airport);


            return $airport;
        }
        else
        {
            return NULL;
        }
    }


    public function http($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'HoboJet PHP Wrapper');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, []);

        try {
            $result = curl_exec($curl);

            if($result)
            {
                curl_close($curl);
                return json_decode($result, TRUE);
            }
            else
            {
                return [];
            }
        }catch (\Exception $e){

        }
    }
}
