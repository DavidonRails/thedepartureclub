<?php

namespace App\Http\Controllers\Admin;

use App\Models\Aircrafts;
use App\Models\AircraftsImages;
use App\Http\Response\Response;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AircraftsController extends Controller
{

	public function get()
	{
		$aircrafts = new Aircrafts();

		$aircrafts_data = $aircrafts->get();

		return Response::success($aircrafts_data);
	}

	public function getById($aircraft_id)
	{

		$aircrafts = new Aircrafts();

		$aircraft_data = $aircrafts->getById($aircraft_id);

		if($aircraft_data)
			return Response::success($aircraft_data);
		else
			return Response::error(['Error while fetching aircraft. Please contact support']);
	}

	public function add(Request $request)
	{

		$data = $request->only([
			'name',
			'manufacturer',
			'seats'
		]);

		$validate = $this->validateAircraft($data);

		if($validate->fails())
		{
			return Response::error($validate->getMessageBag());
		}


		$aircrafts_model = new Aircrafts();

		$save = $aircrafts_model->add($data);

		if($save)
			return Response::success([
				'aircraft_id' => $save
			]);
		else
			return Response::error([
				'message' => 'Error while saving aircraft. Please contact support'
			]);

	}


	public function listAll()
	{

		return Response::success(Aircrafts::listAll());
	}

	public function edit(Request $request)
	{

		$data = $request->only([
			'aircraft_id',
			'name',
			'manufacturer',
			'seats'
		]);

		$validate = $this->validateAircraft($data);

		if($validate->fails())
		{
			return Response::error($validate->getMessageBag());
		}


		$aircrafts_model = new Aircrafts();

		$save = $aircrafts_model->edit($data['aircraft_id'], $data);

		if($save)
			return Response::success([
				'message' => 'Aircraft data saved'
			]);
		else
			return Response::error([
				'message' => 'Error while saving edit. Please contact support'
			]);


	}

	public function validateAircraft(array $data)
	{

		return Validator::make($data, [
			'name' => 'required|min:2|max:255',
			'manufacturer' => 'max:255',
			'seats' => 'required|min:1|max:3'
		]);


	}

	public function deleteById($aircraft_id)
    {
        $aircrafts = new Aircrafts();

        if ($aircraft_data = $aircrafts->deleteById($aircraft_id)) {
            return Response::success(['message' => 'Aircraft deleted']);
        } else
            return Response::error([
                'message' => 'Error while removing aircraft. Please contact support'
            ]);
    }

}
