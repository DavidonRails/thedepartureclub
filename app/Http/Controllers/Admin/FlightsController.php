<?php

namespace App\Http\Controllers\Admin;

use App\Models\Aircrafts;
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
			'status',
			'operator'
		]);

		if($filter['status'] === 'all')
			unset($filter['status']);

		if($filter['operator'] === 'all')
		{
			unset($filter['operator']);
		}
		else
		{
			$filter['operator_id'] = $filter['operator'];
			unset($filter['operator']);
		}


		$filter['admin'] = TRUE;
		$filter['type'] = Flights::FLIGHT_TYPE_DEAL;

		$flights_model = new Flights();

		$flights_data = $flights_model->get($filter, TRUE);

		return Response::success($flights_data);

	}

	public function getById( $flight_id )
	{
		$flights_model = new Flights();

		$flight_data = $flights_model->getById($flight_id);

		return Response::success($flight_data);

	}

	public function add( Request $request )
	{
		$data = $request->only([
			'date',
			'period_from',
			'period_to',
			'price',
			'seats',
			'aircraft_id',
			'aircraft_image_id',
			'origin_airport',
			'destination_airport'
		]);



		$validate = $this->validateFlight($data);


		if($validate->fails())
		{
			return Response::error($validate->getMessageBag());
		}

		$flights_model = new Flights();

		$save = $flights_model->add($data);

		if($save)
			return Response::success([
				'flight_id' => $save
			]);
		else
			return Response::error([
				'message' => 'Error while saving flight. Please contact support'
			]);

	}

	public function edit( Request $request )
	{
		$data = $request->only([
			'flight_id',
			'date',
			'period_from',
			'period_to',
			'price',
			'seats',
			'origin_airport',
			'destination_airport',
			'aircraft_id',
			'aircraft_image_id',
			'status'
		]);

		$validate = $this->validateFlight($data);


		if($validate->fails())
		{
			return Response::error($validate->getMessageBag());
		}

		$flights_model = new Flights();

		$save = $flights_model->edit($data['flight_id'], $data);


		if($save)
			return Response::success([
				'flight_id' => $data['flight_id']
			]);
		else
			return Response::error([
				'message' => 'Error while saving flight. Please contact support'
			]);

	}

	public function delete(Request $request)
	{
		$flights = new Flights();

		$flights->deleteFlight($request->only('flight_id'));
	}


	public function validateFlight( array $data )
	{

		\Validator::extend('aircraft', function($attribute, $value, $parameters)
		{

			if(Aircrafts::where('aircraft_id', '=', $value)->count())
				return TRUE;
			else
				return FALSE;

		});

		return \Validator::make($data, [
			'date' => 'required',
			'period_from' => 'required',
			'period_to' => 'required',
			'price' => 'required|numeric',
			'seats' => 'required|numeric',
			'aircraft_id' => 'required|aircraft'
		], ['aircraft' => 'Please select valid aircraft']);



	}

}
