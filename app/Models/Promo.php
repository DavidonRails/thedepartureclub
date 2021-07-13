<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{


	protected $table = 'promo';


	protected $primaryKey = 'promo_id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'promo_id',
		'name',
		'position',
		'status'
	];


	public static function add()
	{

		$create = Promo::create([
			'status' => 1
		]);

	
		return $create->promo_id;

	}


}
