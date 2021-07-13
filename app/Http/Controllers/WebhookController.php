<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Billing;
use App\Models\BillingPackages;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{

    public function send ( Request $request )
    {
	$type = $request->input('type');
	$customer_id = $request->input('data.object.customer');
        $price_id = $request->input('data.object.items.data.0.price.id');
        $product_id = $request->input('data.object.items.data.0.price.product');

	switch($type) {
		case 'customer.subscription.created':
			print("Subscription Created\n");
			$user = User::where('customer_id', '=', $customer_id )->first();			
			$package = Billing::where('user_id', '=', $user->user_id)->last();
			$package->active = true;
			$package->status = 2; // Active 
			
			
			$package->save();


		break;

		default:
	
	}

        return Response::success([
            'message' => 'Successful'
        ]);

    }


}
