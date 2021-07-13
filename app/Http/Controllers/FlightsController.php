<?php

namespace App\Http\Controllers;

use App\Models\Flights;
use App\Http\Response\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FlightsController extends Controller
{
	public function get( Request $request)
	{

		$filter = $request->only([
			'date_from',
			'date_to',
			'price_from',
			'price_to',
			'origin_longitude',
			'origin_latitude',
			'destination_longitude',
			'destination_latitude',
			'per_page'
		]);



		$filter['status'] = Flights::FLIGHT_STATUS_ACTIVE;
		$filter['type'] = Flights::FLIGHT_TYPE_DEAL;

		$per_page = 20;
		if(!empty($filter['per_page']))
			$per_page = $filter['per_page']; 

		$flights_model = new Flights();

		$flights_data = $flights_model->get($filter, FALSE, $per_page);

		return Response::success($flights_data);


	}

	public function getById(Request $request)
	{

		$data = $request->only(['flight_id']);


		$flights_model = new Flights();

		$flight = $flights_model->getById($data['flight_id']);

		return Response::success($flight);

	}

}
