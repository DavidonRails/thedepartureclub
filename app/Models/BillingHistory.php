<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingHistory extends Model
{

	protected $table = 'billing_history';
    	protected $primaryKey = 'history_id';

	const TYPE_NORMAL = 0;
	const TYPE_FREE = 1;
	const TYPE_CUSTOM = 2;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	
	public function check($package_id)
    {
	    $result = \DB::table($this->table)->where('package_id', '=', $package_id)->where('custom', '=', 0)->where('status', '=', 1);

	    if($result->count())
		    return TRUE;
	    else
		    return FALSE;
    }
	
	public function deactive($user_id)
    {
	    $result = BillingHistory::where('user_id', '=', $user_id)->where('custom', '=', 0)->where('status', '=', 1)->update([
			'status' => 0,
			'payment_agrement_id' => ''
		]);

	    return $result;
    }

	public static function createNew($package_name, $description, $price, $discount)
	{

		$self = new static();

		$bp = new BillingPackages();
		$self->name = $package_name;
		$self->description =  $description;
		$self->price =  $price;
		$self->discount =  $price;
		$self->status = self::STATUS_ACTIVE;

		return $self->save();

	}
	

	public static function getPackages()
	{
		$result = BillingPackages::where('status', '=', self::STATUS_ACTIVE)->where('custom', '=', 0)->get([
			'package_id',
			'name',
			'description',
			'price',
			'type',
			'featured'
		]);


		if(!$result)
			return NULL;


		$return = [];

		foreach ( $result as $key => $item )
		{
			$return[$key] = [
				'package_id' => $item->package_id,
				'name' => $item->name,
				'description' => $item->description,
				'price' => $item->price,
				'free' => ($item->type == self::TYPE_FREE) ? "1" : "0",
				'custom' => ($item->type == self::TYPE_CUSTOM) ? "1" : "0",
				'type' => (string)$item->type,
				'featured' => (string)$item->featured,
			];


		}

		return $return;
	}


	public static function getFreePackage()
	{

		return BillingPackages::where('status', '=', self::STATUS_ACTIVE)->where('type', '=', self::TYPE_FREE)->first();

	}

	public static function getPackage($package_id)
	{

		return BillingPackages::where('package_id', '=', $package_id)->first([
			'flight_price'
		]);

	}

}
