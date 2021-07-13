<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class FlightPassengers extends Model
{

	protected $table = 'flight_passengers';


	protected $primaryKey = 'passenger_id';

	public function addPassengers($flight_id, $booking_id, $data)
	{
        if(is_string( $data )) {
            $data = json_decode($data, TRUE);
        }

		if(count($data) && $booking_id)
		{
			foreach($data as $passenger)
			{
				$this->_add($flight_id, $booking_id, $passenger);
			}
			return TRUE;
		}
		return FALSE;

	}


	public function getByBookingId( $booking_id )
	{
		$data = \App\Models\FlightPassengers::where('booking_id', '=', $booking_id)->get([
			'first_name',
			'last_name',
			'weight',
			'birth_date'
		]);

		if($data)
		{

			$return = [];

			foreach ( $data as $item )
			{
				$return[] = [
					'first_name' => $item->first_name,
					'last_name' => $item->last_name,
					'weight' => $item->weight,
					'birth_date' => $item->birth_date
				];
			}

			return $return;

		}


		return FALSE;
	}

	private function _add($flight_id, $booking_id, $data)
	{

		$self = new static;

		$self->flight_id = $flight_id;
		$self->booking_id = $booking_id;
		$self->first_name = $data['first_name'];
		$self->last_name = $data['last_name'];
		$self->weight = $data['weight'];
		$self->birth_date = date('Y-m-d', strtotime($data['birth_date']));

		$self->save();
	}

}
