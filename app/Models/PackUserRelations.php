<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackUserRelations extends Model
{
	protected $table = 'pack_user_rel';


	protected $primaryKey = 'pack_user_rel_id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'status',
		'owner',
		'pack_id',
		'user_id',
	];


	public static function connect( $pack_id, $user_id, $owner = 0 )
	{

		$check = PackUserRelations::where('pack_id', '=', $pack_id)->where('user_id', '=', $user_id)->where('status', '=', 1)->get(['pack_user_rel_id']);

		if(count($check))
			return FALSE;

		return PackUserRelations::create([
			'user_id' => $user_id,
			'pack_id' => $pack_id,
			'status' => 1,
			'owner' => $owner
		]);


	}

	public static function disconnect( $pack_id, $user_id )
	{

		PackUserRelations::where('pack_id', '=', $pack_id)->where('user_id', '=', $user_id)->update([
			'status' => 0
		]);

	}


	public static function havePack( $user_id )
	{

		$check = PackUserRelations::where(
			'user_id',
			'=',
			$user_id
		)
			->where(
				'owner',
				'=',
				1
			)->get();
		
		if(count($check))
			return $check[0]->pack_id;
		else
			return FALSE;
	}

	public static function isMy( $pack_id )
	{

		$user_id = \Auth::getUser()->user_id;

		if(!$user_id)
			return FALSE;

		$check = PackUserRelations::where('pack_id', '=', $pack_id)->where('user_id', '=', $user_id)->get(['pack_user_rel_id']);

		if(count($check))
			return TRUE;

		return FALSE;

	}
}
