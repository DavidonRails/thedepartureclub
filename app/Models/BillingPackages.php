<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPackages extends Model
{

	protected $table = 'billing_packages';
	protected $primary_key = 'package_id';

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

	public static function createNew($package_name, $description, $price, $discount, $stripe_price_id)
	{

		$self = new static();

		$bp = new BillingPackages();
		$self->name = $package_name;
		$self->description =  $description;
		$self->price =  $price;
		$self->discount =  $discount;
		$self->stripe_price_id = $stripe_price_id;
		$self->status = self::STATUS_ACTIVE;

		return $self->save();

	}
	

	public static function getPackages()
	{
		$result = BillingPackages::where('status', '=', self::STATUS_ACTIVE)->where('custom', '=', 0)->get([
			'package_id',
			'name',
			'description',
			'discount',
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
				'discount' => $item->discount * 100,
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
