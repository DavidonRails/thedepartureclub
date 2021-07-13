<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\EventsImages
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class EventsImages extends Model
{

	protected $table = 'events_images';

	protected $primaryKey = 'image_id';


	public static function add($event_id, $name)
	{

		$static = new static();
		$static->event_id = $event_id;
		$static->name = $name;
		$static->active = 3;
		$static->default = 0;

		$save = $static->save();

		if($save)
			return $static->image_id;
		else
			return FALSE;

	}

	public static function setDefault($event_id, $image_id)
	{
		EventsImages::where('event_id', $event_id)->update([
			'default' => 0
		]);

		return EventsImages::where('image_id', $image_id)->update([
			'default' => 1
		]);
	}

	public static function deleteImage($image_id)
	{

		$image = EventsImages::where('image_id', $image_id);

		if($image->get()->toArray()[0]['default'])
			return [
				'error' => 'You can`t delete default image'
			];

		return EventsImages::where('image_id', $image_id)->update([
			'active' => 0
		]);
	}

	public function getByEventId($event_id)
	{

		$images = \DB::table($this->table)->where('event_id', '=', $event_id)->where('active', '=', 1)->orderBy('default', 'desc');

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
