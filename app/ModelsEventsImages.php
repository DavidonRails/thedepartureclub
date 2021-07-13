<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\AircraftsImages
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class AircraftsImages extends Model
{

	protected $table = 'aircrafts_images';

	protected $primaryKey = 'image_id';


	public static function add($aircraft_id, $name)
	{

		$static = new static();
		$static->aircraft_id = $aircraft_id;
		$static->name = $name;
		$static->active = 3;
		$static->default = 0;

		$save = $static->save();

		if($save)
			return $static->image_id;
		else
			return FALSE;

	}

	public static function setDefault($aircraft_id, $image_id)
	{
		AircraftsImages::where('aircraft_id', $aircraft_id)->update([
			'default' => 0
		]);

		return AircraftsImages::where('image_id', $image_id)->update([
			'default' => 1
		]);
	}

	public static function deleteImage($image_id)
	{

		$image = AircraftsImages::where('image_id', $image_id);

		if($image->get()->toArray()[0]['default'])
			return [
				'error' => 'You can`t delete default image'
			];

		return AircraftsImages::where('image_id', $image_id)->update([
			'active' => 0
		]);
	}

	public function getByAircraftId($aircraft_id)
	{

		$images = \DB::table($this->table)->where('aircraft_id', '=', $aircraft_id)->where('active', '=', 1)->orderBy('default', 'desc');

		if($images->count() > 0)
		{
			$return = [];

			foreach ( $images->get() as $image )
			{
				$return[] = [
					'image_id' => $image->image_id,
					'image' => $image->path . $image->name,
					'default' => $image->default
				];
			}

			return $return;

		}
		else
			return FALSE;

	}

}
