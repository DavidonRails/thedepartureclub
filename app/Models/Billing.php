<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Billing
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Billing extends Model
{

	protected $fillable = ['history_id', 'user_id', 'package_id', 'status', 'paypal_agrement_id'];

	protected $primaryKey = 'history_id';

	protected $table = 'billing_history';

	const STATUS_ACTIVE = 1;
	const STATUS_CANCELED = 0;
	const STATUS_PENDING = 2;
	const STATUS_INVALID = 3;


	public static function createNew($user_id, $package_id)
	{

		$self = new static();

		Billing::where('user_id', '=', $user_id)->where('status', '=', self::STATUS_ACTIVE)->update(['status' => self::STATUS_CANCELED]);


		$self->user_id = $user_id;
		$self->package_id = $package_id;
		$self->status = self::STATUS_ACTIVE;

		return $self->save();

	}
	
	public static function activatePending($user_id, $paypal_agrement_id)
	{

		$pending = Billing::where( 'user_id', '=', $user_id )
			->where( 'status', '=', self::STATUS_PENDING )
			->get();

		foreach ( $pending as $item )
		{
			$item->update([
				'status' => self::STATUS_INVALID
			]);
		}


		$active = Billing::where( 'user_id', '=', $user_id )
			->where( 'status', '=', self::STATUS_ACTIVE )
			->get();

		if(count($active))
			foreach ( $active as $item )
			{
				$item->update([
					'status' => self::STATUS_INVALID
				]);
			}


		$pending->last()->update([
			'status' => self::STATUS_ACTIVE,
			'paypal_agrement_id' => $paypal_agrement_id
		]);
		
	}

	public static function getUserPackage( $user_id )
	{

		$package = Billing::where('user_id', '=', $user_id)->where('status', '=', self::STATUS_ACTIVE)->first([
			'package_id'
		]);

		\Log::info($package);

		if(!$package)
		{

			// There should be no free packages.
			$free_package = BillingPackages::where('type', '=', 1)->first();
			\Log::info($free_package);
			self::createNew( $user_id,  $free_package->package_id );

			$package = Billing::where('user_id', '=', $user_id)->first([
				'package_id'
			]);
		}

		return $package->package_id;


	}


	public static function createPending($user_id, $package_id)
	{


		$save = Billing::create([
			'user_id' => $user_id,
			'package_id' => $package_id,
			'status' => self::STATUS_PENDING
		]);


		return $save->history_id;

	}


	public static function cancelSubscription($history_id)
	{

		$current = Billing::where( 'history_id', '=', $history_id )->first();

		$free_package = BillingPackages::getFreePackage();

		Billing::createNew( $current->user_id, $free_package->package_id );

	}


	public static function getFlightPrice()
	{


		$user = \Auth::getUser();


		$membership = NULL;
		if($user)
		{

			$user_package_id = Billing::getUserPackage( $user->user_id );

			$user_package = BillingPackages::getPackage( $user_package_id );

			return $user_package->flight_price;
		}
		else
		{
			$free_package = BillingPackages::getFreePackage();
			return (string)$free_package->flight_price;
		}

	}

}
