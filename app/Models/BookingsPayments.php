<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class BookingsPayments extends Model
{
    protected $table = 'bookings_payments';

	protected $primaryKey = 'booking_payment_id';


	public function addPayPal( $booking_id, $data )
	{

		$this->status = 1;
		$this->booking_id = $booking_id;
		$this->data = json_encode($data);


		$save = $this->save();

		if($save)
			return $this->booking_payment_id;

		return FALSE;

	}
	


}
