<?php

namespace App\Http\Controllers\Admin;

use App\Http\Response\Response;
use App\Models\AircraftImages;
use App\Models\EventsImages;
use App\Models\Events;
use App\Models\Flights;
use App\Models\Promo;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UploadsController extends Controller
{

	public function upload(Request $request)
	{

		if($request->hasFile('file'))
		{

			switch($request->get('data')['type'])
			{
				case 'aircraft':

					return $this->uploadAircraftImage($request);

					break;

				case 'event':

					return $this->uploadEventImage($request);

					break;
	
					
				case 'promo':

					return $this->uploadPromoImage($request);

					break;
			}

		}

	}

	private function uploadAircraftImage(Request $request)
	{


		$data['aircraft_id'] = $request->get('data')['aircraft_id'];
		$data['name'] = $request->get('data')['name'] . $request->file('file')->getExtension();

		$data['name'] = str_slug($data['name']) . '.' . $request->file('file')->getClientOriginalExtension();

		$image_id = AircraftImages::add($data['aircraft_id'], $data['name']);

		$image_path = 'data/images/aircrafts/' . md5($image_id) . '/';
		$path = base_path() . '/public/' . $image_path;

		$upload = $request->file('file')->move(
			$path,
			$data['name']
		);

		if($upload)
		{

			if(isset($request->get('data')['new']))
			AircraftImages::setDefault($data['aircraft_id'], $image_id);


			if(isset($request->get('data')['flight_id']))
				Flights::where('flight_id', '=', $request->get('data')['flight_id'])->update([
					'aircraft_image_id' => $image_id
				]);


				AircraftImages::where('image_id', $image_id)->update([
				'path' => $image_path,
				'active' => 1
			]);

			return Response::success([
				'message' => 'Aircraft added'
			]);
		}


		return Response::error([
			'message' => 'Error while saving aircraft image. Please contact support'
		]);


	}

	private function uploadEventImage(Request $request)
	{
		
		$data['event_id'] = $request->get('data')['event_id'];
		
		$data['name'] = $request->get('data')['name'] . $request->file('file')->getExtension();

		$data['name'] = str_slug($data['name']) . '.' . $request->file('file')->getClientOriginalExtension();

		$exist_event_image = EventsImages::where('event_id', '=', $data['event_id'])->first();
		
		if(!isset($exist_event_image)) {
			// New Event Image for New Event
			$image_id = EventsImages::add($data['event_id'], $data['name']);
		} else {
			// Exist Image ID
			$image_id = $exist_event_image->image_id;
		}

		$image_path = 'data/images/events/' . md5($image_id) . '/';
		$path = base_path() . '/public/' . $image_path;

		$upload = $request->file('file')->move(
			$path,
			$data['name']
		);

		if($upload)
		{
			if($request->get('data')['is_new_event'] == "new_event") { // New Event
				EventsImages::setDefault($data['event_id'], $image_id);

				if(isset($request->get('data')['event_id'])) {
					Events::where('id', '=', $data['event_id'])->update([
						'event_image_id' => $image_id
					]);
				}

				EventsImages::where('image_id', $image_id)->update([
					'path' => $image_path,
					'active' => 1
				]);
				
				return Response::success([
					'message' => 'Event image added'
				]);
			} else { // Edit Event
				Events::where('id', '=', $data['event_id'])->update([
					'event_image_id' => $image_id
				]);
				
				EventsImages::where('image_id', $image_id)->update([
					'name' => $data['name'],
					'path' => $image_path,
				]);
				
				return Response::success([
					'message' => 'Event image updated',
					'image_name' => $data['name'],
					'image_path' => $image_path,
				]);
			}
		}

		return Response::error([
			'message' => 'Error while saving event image. Please contact support'
		]);
	}

	private function uploadPromoImage(Request $request)
	{


		$img = \Image::make($request->file('file')->getPathname());
		$height = $img->height();
		$width = $img->width();

		if($height != 320 || $width != 640){
			return Response::error([
				'message' => 'Image resolution should be 640x320 px'
			]);
		}

		$image_id = Promo::add();

		$image_path = 'data/images/promo/';
		$path = base_path() . '/public/' . $image_path;

		$name = md5($image_id) . '.' . $request->file('file')->getClientOriginalExtension();

		$request->file('file')->move(
			$path,
			$name
		);

	}

}
