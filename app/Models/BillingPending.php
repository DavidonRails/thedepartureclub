<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPending extends Model
{

	protected $fillable = ['user_id', 'package_id', 'status'];

	protected $table = 'billing_pending';

	protected $primaryKey = 'billing_pending_id';
	
	const STATUS_ACTIVE = 1;
	const STATUS_DONE = 0;

    public function createPending($user_id, $package_id)
    {

	    $response = $this->create([
		    'user_id' => $user_id,
		    'package_id' => $package_id,
		    'status' => self::STATUS_ACTIVE
	    ]);


	    return $response->billing_pending_id;


    }


}
