<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function getLocation(Request $request)
    {
        $data = $request->only(['longitude', 'latitude']);

        $url = "https://maps.googleapis.com/maps/api/geocode/json?";
        $url .= "latlng=".$data['latitude'].",".$data['longitude'];
        $url .= "&libraries=places";
        $url .= "&types=(geocode)";
        $url .= "&sensor=false";
        $url .= "&key=" . env('GOOGLE_CLOUD_API_ID');

        $geocode = $this->http($url);

        if(!isset($geocode['status']) && $geocode['status'] != 'OK')
            return Response::success([]);


        $return = [];
        foreach($geocode['results'] as $location)
        {


            $return[] = [
                'formatted_address' => $location['formatted_address'],
                'geometry' => [
                    'lat' => $location['geometry']['location']['lat'],
                    'lng' => $location['geometry']['location']['lng']
                ]
            ];
        }


        if(count($return))
        {
            return Response::success($return);
        }
        else
            return Response::success([]);
    }
}
