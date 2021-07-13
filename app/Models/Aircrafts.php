<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Aircrafts
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Aircrafts extends Model
{


	protected $table = 'aircrafts';

	protected $primaryKey = 'aircraft_id';

	public function add($data)
	{

		$this->name = $data['name'];
		$this->manufacturer = $data['manufacturer'];
		$this->seats = $data['seats'];

		$save = $this->save();

		if($save)
			return $this->aircraft_id;
		else
			return FALSE;



	}

	public function edit($aircraft_id, $data)
	{
		unset($data['aircraft_id']);
		$edit = Aircrafts::where('aircraft_id', '=', $aircraft_id)->update($data);

		if($edit)
			return TRUE;
		else
			return FALSE;
	}

	public function deleteById($aircraft_id)
    {
        return Aircrafts::where('aircraft_id', '=', $aircraft_id)->delete();
    }

	public static function listAll($type = 0)
	{

		$data = \DB::table('aircrafts')
	                ->select(
		                'aircrafts.*',
		                'aircrafts_images.image_id',
		                'aircrafts_images.name AS image_name',
		                'aircrafts_images.path AS image_path',
		                'aircrafts_images.default AS image_default',
		                'aircrafts_images.active AS image_active'
	                )
	                ->join('aircrafts_images', function($join){
		                $join
			                ->on('aircrafts.aircraft_id', '=', 'aircrafts_images.aircraft_id')
			                ->where('aircrafts_images.default', '=', 1);
					})
	                ->where('aircrafts.active', '=', 1)
		            ->where('aircrafts.type', '=', $type)
					->get();

		$return = [];

		if(!count($data))
			return $return;


		foreach($data as $k => $val)
		{

			$return[] = [
				'aircraft_id' => $val->aircraft_id,
				'aircraft_image_id' => $val->image_id,
				'name' => $val->name,
				'image' => $val->image_path . $val->image_name
			];
		}

		return $return;

	}

	public function getById($aircraft_id)
	{
		$aircraft = Aircrafts::where($this->primaryKey, '=', $aircraft_id)->get(['*']);

		if($aircraft->count())
		{
			$image = AircraftsImages::where('aircraft_id', '=', $aircraft_id)->first();

			$aircraft[0]->image_id = $image->image_id;
			return $aircraft[0];
		}
		else
			return FALSE;
	}

	public function get($type = 0)
	{
		$query = \DB::table($this->table)
			->select(
				'aircrafts.*',
				'aircrafts_images.image_id',
				'aircrafts_images.name AS image_name',
				'aircrafts_images.path AS image_path',
				'aircrafts_images.default AS image_default',
				'aircrafts_images.active AS image_active'
			)
			->join('aircrafts_images', function($join){
				$join
					->on($this->table . '.aircraft_id', '=', 'aircrafts_images.aircraft_id')
					->where('aircrafts_images.default', '=', 1);
			});

		$query->where('aircrafts.active', '=', 1);
		$query->where('aircrafts.type', '=', $type);

		$aircrafts = $query->paginate(10);

		if(!$aircrafts)
			return FALSE;

		$return = [];

		foreach($aircrafts->items() as $aircraft)
		{


			$return['data'][] = [
				'aircraft_id' => $aircraft->aircraft_id,
				'name' => $aircraft->name,
				'manufacturer' => $aircraft->manufacturer,
				'seats' => $aircraft->seats,
				'flight_price' => $aircraft->flight_price,
				'image_id' => $aircraft->image_id,
				'image_name' => $aircraft->image_name,
				'image_path' => $aircraft->image_path
			];

		}

		$return['pagination'] = [
			'total' => $aircrafts->total(),
			'last_page' => $aircrafts->lastPage(),
			'next_page' => str_replace(url('api') . '/', '', $aircrafts->nextPageUrl()),
			'prev_page' => str_replace(url('api') . '/', '', $aircrafts->previousPageUrl()),
			'per_page' => $aircrafts->perPage(),
			'current_page' => $aircrafts->currentPage()
		];

		return $return;
	}


}
