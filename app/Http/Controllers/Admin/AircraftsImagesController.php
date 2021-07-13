<?php

namespace App\Http\Controllers\Admin;

use App\Models\AircraftsImages;
use Illuminate\Http\Request;
use App\Http\Response\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AircraftsImagesController extends Controller
{

	public function get($aircraft_id)
	{

		$aircrafts_images_model = new AircraftsImages();

		$aircrafts_images = $aircrafts_images_model->getByAircraftId($aircraft_id);

		if($aircrafts_images)
			return Response::success($aircrafts_images);
		else
			return Response::success([]);
	}

	public function delete($image_id)
	{
		$delete = AircraftsImages::deleteImage($image_id);

		if(is_array($delete))
			return Response::error(['message' => $delete]);
		else
			return Response::success(['message' => 'Image deleted']);

	}

	public function setDefault(Request $request)
	{

		$data = $request->only([
			'aircraft_id',
			'image_id'
		]);

		$default = AircraftsImages::setDefault($data['aircraft_id'], $data['image_id']);

		if($default)
			return Response::success(['message' => 'Image is now default']);
		else
			return Response::error(['message' => 'Error while deleting aircraft image. Please contact support']);

	}

}
