<?php

namespace App\Http\Controllers\Admin;

use App\Http\Response\Response;
use App\Models\Airports;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AirportsController extends Controller
{


    public function searchAirportsByIata( Request $request )
    {

        $data = $request->only(['string']);
        $string = $data['string'];

        $airports = new Airports();

        $airport = $airports->searchIata($string);
        if($airport)
            return Response::success($airport);
        else
            return $this->searchApi($string);





    }

    public function searchApi( $string )
    {
        $airport = $this->http('http://www.airport-data.com/api/ap_info.json?icao=' . $string);

        if($airport['status'] == 200 && $airport['latitude'] && $airport['longitude'] && ($airport['latitude'] != '0.000000' || $airport['longitude'] != '0.000000'))
        {
            $airports = new Airports();
            $airports->addNew($airport);


            return Response::success($airport);
        }
        else
        {
            return Response::error(['message' => 'No airports matching ICAO code "' .$string . '"']);
        }
    }

}
