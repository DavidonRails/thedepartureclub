<?php

namespace App\Models;

use App\Http\Response\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\BillingHistory;
use App\Models\BillingPackages;

/**
 * App\Flights
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Flights extends Model
{

	const FLIGHT_TYPE_DEAL = 0;
	const FLIGHT_TYPE_CHARTER = 1;
	
	const FLIGHT_STATUS_PENDING = 0;
	const FLIGHT_STATUS_ACTIVE = 1;
	const FLIGHT_STATUS_CANCELED = 2;
	const FLIGHT_STATUS_BOOKED = 3;
	const FLIGHT_STATUS_EXPIRED = 4;
	const FLIGHT_STATUS_DELETED = 5;

	protected $table = 'flights';

	protected $primaryKey = 'flight_id';

	protected $fillable = [
		'status',
		'origin_location',
		'destination_location',
		'origin_lon',
		'origin_lat',
		'destination_lon',
		'destination_lat',
		'origin_airport_iata',
		'destination_airport_iata',
		'origin_airport_info',
		'destination_airport_info'
	];


	public function get($filter = [], $web = FALSE, $limit = 5)
	{

		$origin_sql = NULL;
		$destination_sql = NULL;
		
		$user = \Auth::getUser();
		if($user) {
			$billing_package = BillingHistory::find(['user_id'=>$user->user_id])->first();
			$package = BillingPackages::where(['package_id'=>$billing_package->package_id])->first();
		}

		if(!empty($filter['origin_longitude']) && !empty($filter['origin_latitude']))
		{

			$origin_latitude = $filter['origin_latitude'];
			$origin_longitude = $filter['origin_longitude'];

			$origin_sql =
				"
					( 3959 
						* acos( cos( radians($origin_latitude) ) 
						* cos( radians( origin_lat ) ) 
						* cos( radians( origin_lon ) - radians($origin_longitude) ) + sin( radians($origin_latitude) ) 
						* sin(radians(origin_lat)) ) 
					) AS origin_distance
				";

			$origin_sql = \DB::raw($origin_sql);


		}

		if(!empty($filter['destination_longitude']) && !empty($filter['destination_latitude']))
		{


			$destination_latitude = $filter['destination_latitude'];
			$destination_longitude = $filter['destination_longitude'];


			$destination_sql =
				"
					( 3959 
						* acos( cos( radians($destination_latitude) ) 
						* cos( radians( destination_lat ) ) 
						* cos( radians( destination_lon ) - radians($destination_longitude) ) + sin( radians($destination_latitude) ) 
						* sin(radians(destination_lat)) ) 
					) AS destination_distance
				";

			$destination_sql = \DB::raw($destination_sql);

		}


		$query = \DB::table($this->table)
			->select(
				$this->table . '.*',
				'aircrafts.name AS aircraft_name',
				'aircrafts_images.name AS image_name',
				'aircrafts_images.path AS image_path'
			)
			->leftJoin('aircrafts', function($join){
				$join->on($this->table . '.aircraft_id', '=', 'aircrafts.aircraft_id');
			})
			->leftJoin('aircrafts_images', function($join){
				$join
					->on($this->table . '.aircraft_image_id', '=', 'aircrafts_images.image_id');
			});

		if(isset($filter['status']))
			$query->where($this->table . '.status', '=', $filter['status']);

		if(isset($filter['type']))
			$query->where($this->table . '.type', '=', $filter['type']);


		if(!empty($filter['date_from']) && !empty($filter['date_to']))
			$query->whereBetween('flights.flight_start', [new \DateTime($filter['date_from']), new \DateTime($filter['date_to'])]);


		if((isset($filter['price_from']) && !is_null($filter['price_from'])) && (isset($filter['price_to']) && !is_null($filter['price_to']))) {
			if($user != null) {
				$user_id = $user->user_id;
				$user_model = new User();
				$package = $user_model->getByIdWithPackage($user_id);
				$discount = (float)$package->discount;
				if(abs($discount) != 1)
					$query->whereBetween('flights.price', [(int)($filter['price_from'] / ( 1 - $discount )), (int)($filter['price_to'] / ( 1 - $discount )) ]);
				else
					$query->whereBetween('flights.price', [(int)$filter['price_from'], (int)$filter['price_to']]);
			} else {
				$query->whereBetween('flights.price', [(int)$filter['price_from'], (int)$filter['price_to']]);
			}
		
		}

		

		if($origin_sql)
		{
			$query->addSelect($origin_sql);
			$query->having('origin_distance', '<', 100);
		}


		if($destination_sql)
		{
			$query->addSelect($destination_sql);
			$query->having( 'destination_distance', '<', 100 );
		}


		$query->orderBy('flight_start', 'asc');
		
		if((isset($filter['admin']) && $filter['admin']) && (isset($user) && $user->role == 'operator'))
		{
			$query->where('operator_id', '=', $user->user_id);
		}

		if(!(isset($filter['admin']) && $filter['admin']))
			$query->where('flights.flight_start', '>=', date('Y-m-d H:i:s'));


		if(isset($filter['operator_id']))
			$query->where('flights.operator_id', '=', $filter['operator_id']);

		if(isset($filter['flight_id']))
			$query->where('flights.flight_id', '=', $filter['flight_id']);

		$simple_paginate = FALSE;
		if($origin_sql || $destination_sql)
		{
			$flights = $query->simplePaginate($limit);
			$simple_paginate = TRUE;
		}
		else
		{

			$flights = $query->paginate($limit);
		}


		if(!$flights)
			return FALSE;

		$return = [
			'data' => [],
			'pagination' => []
		];

		foreach($flights->items() as $flight)
		{

			if(!$web)
				$image = url('') . '/' . $flight->image_path . $flight->image_name;
			else
				$image = $flight->image_path . $flight->image_name;

			$price = $flight->price;
			if(!empty($package)) {
				// Apply discount
				$price = $flight->price - ((float)$flight->price * $package->discount);
			}

			$return['data'][] = [
				'flight_identification' => $flight->flight_identification,
				'hash' => $flight->hash,
				'flight_start' => date('Y-m-d', strtotime($flight->flight_start)),
				'route_origin' => $flight->origin_location,
				'route_destination' => $flight->destination_location,
				'flight_time' => $flight->flight_time,
				'active' => $flight->status,
				'status' => $flight->status,
//				'price' => Billing::getFlightPrice(),
				'price' => $flight->price,
				'seats' => $flight->seats,
				'date' => date('Y-m-d', strtotime($flight->flight_start)),
				'period_from' => date('H:i', strtotime($flight->flight_start)),
				'period_to' => date('H:i', strtotime($flight->flight_end)),
				'aircraft_name' => $flight->aircraft_name,
				'aircraft_image' => $image,
				'discount' => $price,
				'flight_id' => $flight->flight_id,
				'until_takeoff' => round((strtotime($flight->flight_start) - strtotime('NOW')) / 86400),
				'flight_start_human' => date('M j, Y', strtotime($flight->flight_start)),
                'flight_start_short' => date('M dd'),
			];


			if(strtotime('NOW') > strtotime($flight->flight_start))
			{
				Flights::where('flight_id', '=', $flight->flight_id)->update([
					'status' => Flights::FLIGHT_STATUS_EXPIRED
				]);
			}

		}


		if($web)
			$return['pagination'] = [
				'total' => (!$simple_paginate) ? $flights->total() : NULL,
				'last_page' => (!$simple_paginate) ? $flights->lastPage() : NULL,
				'next_page' => str_replace(url('api') . '/', '', (!$simple_paginate) ? $flights->nextPageUrl() : NULL),
				'prev_page' => str_replace(url('api') . '/', '', (!$simple_paginate) ? $flights->previousPageUrl() : NULL),
				'per_page' => (!$simple_paginate) ? $flights->perPage() : NULL,
				'current_page' => (!$simple_paginate) ? $flights->currentPage() : NULL
			];
		else
			$return['pagination'] = [
				'total' => (!$simple_paginate) ? $flights->total() : NULL,
				'last_page' => (!$simple_paginate) ? $flights->lastPage() : NULL,
				'next_page' => (!$simple_paginate) ? $flights->nextPageUrl() : NULL,
				'prev_page' => (!$simple_paginate) ? $flights->previousPageUrl() : NULL,
				'per_page' => (!$simple_paginate) ? $flights->perPage() : NULL,
				'current_page' => (!$simple_paginate) ? $flights->currentPage() : NULL
			];

		return $return;

    }

	public function getByLocation( $origin_longitude, $origin_latitude, $destination_longitude, $destination_latitude )
	{


		$result = \DB::select(
			"
			SELECT 
			*, 
			( 3959 
				* acos( cos( radians($origin_latitude) ) 
				* cos( radians( origin_lat ) ) 
				* cos( radians( origin_lon ) - radians($origin_longitude) ) + sin( radians($origin_latitude) ) 
				* sin(radians(origin_lat)) ) 
			) AS origin_distance,
			( 3959 
				* acos( cos( radians($destination_latitude) ) 
				* cos( radians( destination_lat ) ) 
				* cos( radians( destination_lon ) - radians($destination_longitude) ) + sin( radians($destination_latitude) ) 
				* sin(radians(destination_lat)) ) 
			) AS destination_distance
			FROM flights 
			HAVING origin_distance < 100 AND destination_distance < 100
            "
		);


		if($result)
			return $result;
		else
			return FALSE;

	}

	public function getById( $flight_id )
	{

		$flight = \DB::table($this->table)
					->select(
						$this->table . '.*',
						'aircrafts.name AS aircraft_name',
						'aircrafts_images.path AS image_path',
						'aircrafts_images.name AS image_name',
						'aircrafts_images.image_id AS image_id'
					)
					->leftJoin('aircrafts_images', function($join){
						$join
							->on($this->table . '.aircraft_image_id', '=', 'aircrafts_images.image_id');
					})
					->leftJoin('aircrafts', function($join){
						$join
							->on($this->table . '.aircraft_id', '=', 'aircrafts.aircraft_id');
					})
					->where('flight_id', '=', $flight_id);

		$return = [];
		if($flight->count())
		{
			$data = (array)$flight->get()[0];

			$return['origin_location'] = $data['origin_location'];
			$return['origin_lon'] = $data['origin_lon'];
			$return['origin_lat'] = $data['origin_lat'];
			$return['destination_location'] = $data['destination_location'];
			$return['destination_lon'] = $data['destination_lon'];
			$return['destination_lat'] = $data['destination_lat'];
			$return['date'] = date('Y-m-d', strtotime($data['flight_start']));
			$return['period_from'] = date('H:i', strtotime($data['flight_start']));
			$return['period_to'] = date('H:i', strtotime($data['flight_end']));
			$return['price'] = $data['price'];
			$return['seats'] = $data['seats'];
			$return['aircraft_id'] = $data['aircraft_id'];
			$return['aircraft_name'] = $data['aircraft_name'];
			$return['aircraft_image_id'] = $data['image_id'];
			$return['aircraft_image'] = url($data['image_path'] . $data['image_name']);

			$origin_airport_info = json_decode($data['origin_airport_info'], TRUE);
			$return['origin_airport_name'] = $origin_airport_info['name'];
			$return['origin_airport_iata'] = $origin_airport_info['iata'];
			$return['origin_airport_data'] = $origin_airport_info;
			$destination_airport_info = json_decode($data['destination_airport_info'], TRUE);
			$return['destination_airport_name'] = $destination_airport_info['name'];
			$return['destination_airport_iata'] = $destination_airport_info['iata'];
			$return['destination_airport_data'] = $destination_airport_info;

			$return['until_takeoff'] = round((strtotime($data['flight_start']) - strtotime('NOW')) / 86400);
			$return['flight_start_human'] = date('M j, Y', strtotime($data['flight_start']));

			$return['status'] = $data['status'];

			return $return;
		}
		else
			return NULL;

	}

	public function add($data)
	{

		$this->origin_location = $data['origin_airport']['location'];
		$this->origin_lat = $data['origin_airport']['latitude'];
		$this->origin_lon = $data['origin_airport']['longitude'];
		$this->origin_airport_iata = $data['origin_airport']['iata'];
		unset($data['origin_airport']['link']);
		unset($data['origin_airport']['status']);
		$this->origin_airport_info = json_encode($data['origin_airport']);
		$this->destination_location = $data['destination_airport']['location'];
		$this->destination_lat = $data['destination_airport']['latitude'];
		$this->destination_lon = $data['destination_airport']['longitude'];
		$this->destination_airport_iata = $data['destination_airport']['iata'];
		unset($data['destination_airport']['link']);
		unset($data['destination_airport']['status']);
		$this->destination_airport_info = json_encode($data['destination_airport']);
		
		$this->price = $data['price'];
		$this->seats = $data['seats'];

		$flight_date_start = date('Y-m-d', strtotime($data['date']));
		$flight_date_end = date('Y-m-d', strtotime($data['date']));

		$this->flight_start = date('Y-m-d H:i:s', strtotime($flight_date_start . ' ' . $data['period_from']));
		$this->flight_end = date('Y-m-d H:i:s', strtotime($flight_date_end . ' ' . $data['period_to']));

		if(explode(':', $data['period_from'])[0] > explode(':', $data['period_to'])[0])
			$this->flight_end = date('Y-m-d H:i:s', strtotime($this->flight_end . " + 1 DAY"));


		$datetime1 = new \DateTime($this->flight_start);
		$datetime2 = new \DateTime($this->flight_end);

		$interval = $datetime1->diff($datetime2);

		$this->flight_time = ($interval->days * 24) + $interval->h;

		$this->aircraft_id = $data['aircraft_id'];
		$this->aircraft_image_id = $data['aircraft_image_id'];
		$this->status = 1;

		$this->operator_id = \Auth::getUser()->user_id;

		$this->type = self::FLIGHT_TYPE_DEAL;

		$save = $this->save();
		\Artisan::call('flights:alerts', [
			'flight_id' => $this->flight_id
		]);



		if($save)
			return $this->flight_id;
		else
			return FALSE;


	}

	public function edit( $flight_id, $data )
	{

		$flight = Flights::where('flight_id', '=', $flight_id)->first();


		$flight->origin_location = $data['origin_airport']['location'];
		$flight->origin_lat = $data['origin_airport']['latitude'];
		$flight->origin_lon = $data['origin_airport']['longitude'];
		$flight->origin_airport_iata = $data['origin_airport']['iata'];
		unset($data['origin_airport']['link']);
		unset($data['origin_airport']['status']);
		$flight->origin_airport_info = json_encode($data['origin_airport']);
		$flight->destination_location = $data['destination_airport']['location'];
		$flight->destination_lat = $data['destination_airport']['latitude'];
		$flight->destination_lon = $data['destination_airport']['longitude'];
		$flight->destination_airport_iata = $data['destination_airport']['iata'];
		unset($data['destination_airport']['link']);
		unset($data['destination_airport']['status']);
		$flight->destination_airport_info = json_encode($data['destination_airport']);


		$flight->price = $data['price'];
		$flight->seats = $data['seats'];

		$flight_date_start = date('Y-m-d', strtotime($data['date']));
		$flight_date_end = date('Y-m-d', strtotime($data['date']));

		$flight->flight_start = date('Y-m-d H:i:s', strtotime($flight_date_start . ' ' . $data['period_from']));
		$flight->flight_end = date('Y-m-d H:i:s', strtotime($flight_date_end . ' ' . $data['period_to']));

		if(explode(':', $data['period_from'])[0] > explode(':', $data['period_to'])[0])
			$flight->flight_end = date('Y-m-d H:i:s', strtotime($flight->flight_end . " + 1 DAY"));


		$datetime1 = new \DateTime($flight->flight_start);
		$datetime2 = new \DateTime($flight->flight_end);

		$interval = $datetime1->diff($datetime2);

		$flight->flight_time = $interval->format('%h.%i');

		$flight->aircraft_id = $data['aircraft_id'];
		$flight->aircraft_image_id = $data['aircraft_image_id'];
		$flight->status = $data['status'];
		
		$update = $flight->save();


		\Artisan::call('flights:alerts', [
			'flight_id' => $flight_id
		]);


		if($update)
			return TRUE;
		else
			return FALSE;

	}


	public function deleteFlight( $flight_id )
	{
		$flight = Flights::where('flight_id', '=', $flight_id)->first();

		if($flight)
			$flight->update([
				'status' => self::FLIGHT_STATUS_DELETED
			]);

		return TRUE;

	}

	public function addCustomFlight($data)
	{


		$this->type = self::FLIGHT_TYPE_CHARTER;
		$this->status = self::FLIGHT_STATUS_PENDING;

		$this->price = $data['price'];
		$this->flight_start = date('Y-m-d H:i:s', strtotime($data['flight_date_start']));
		
		$this->origin_lon = $data['origin_lon'];
		$this->origin_lat = $data['origin_lat'];
		$this->origin_location = $data['origin_location'];

		$this->destination_lon = $data['destination_lon'];
		$this->destination_lat = $data['destination_lat'];
		$this->destination_location = $data['destination_location'];

		$this->aircraft_id = $data['aircraft_id'];
		$this->aircraft_image_id = $data['aircraft_image_id'];
		$this->seats = $data['aircraft_seats'];

		$this->operator_id = 0;

		$save = $this->save();


		if($save)
			return $this->flight_id;
		else
			return FALSE;

	}

//	public function getCustom()
//	{
//
//		$flights = $this->get([
//			'status' => 0,
//			'type' => 1
//		]);
//
//		if(count($flights))
//			return $flights['data'];
//		else
//			return NULL;
//
//	}

	public static function setImage($flight_id, $image_id)
	{

		Flights::where('flight_id', '=', $flight_id)->update([
			'aircraft_image_id' => $image_id
		]);


	}
}
