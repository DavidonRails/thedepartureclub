<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Billing;
use App\Models\BillingPackages;
use App\Models\BillingPending;
use App\Models\BillingHistory;
use App\Models\User;
use App\Models\UserTokens;
use App\Services\PayPalService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
	public function getCustomerId ( Request $request )
	{
        $data = $request->only( [
            'user_id',
        ] );
		
		// Set your secret key. Remember to switch to your live secret key in production!
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
		
		/*
		$session = \Stripe\Checkout\Session::create([
		  'payment_method_types' => ['card'],
		  'line_items' => [[
			'price' => '{{'price_1GtFYwB8H3AkX9IhREeTGBiN'}}',
			'quantity' => 1,
			]],
		  'mode' => 'subscription',
		  'success_url' => 'https://example.com/success?session_id={CHECKOUT_SESSION_ID}',
		  'cancel_url' => 'https://example.com/cancel',
		]);
		*/
		
		$user = User::where('user_id', '=', $data['user_id'])->first();
		
		$customer_id = $user['customer_id'];
		
		if( empty($customer_id) ) {
			
			$customer = \Stripe\Customer::create([
				'email' => $user['email']
			]);
			
			User::where('user_id', '=', $data['user_id'])->update([
				'customer_id' => $customer['id']
			]);
			
			$customer_id = $customer['id'];
		}
		
		return $customer_id;
	}
	
	public function subscribed_notification($data) {
		/*
		// Using Laravel Mail Function
		\Mail::send('email.subscribed_notification', $data, function($message) use($data){
			$message
				->from('noreplay@thedepartureclub.com')
				->to( 'josh@thedepartureclub.com' )
				->cc('sshere@exqsd.com')
				->subject("User Subscription Paid!");
		});
		*/
		
        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

 		$mail
			->addTo('memberships@thedepartureclub.com')
 			->setFromName(" ")
 			->addHeader("TDC", "1")
 			->setFrom('noreplay@thedepartureclub.com')
 			->setSubject('Admin Notification - New subscription has been created.')
 			->setHtml(' ')
 			
 			->addSubstitution( ':DATE_SEND', [date("F j, Y, g:i a")])
 			->addSubstitution( ':NAME', [$data['name']])
 			->addSubstitution( ':EMAIL', [$data['email']])
 			->addSubstitution( ':PHONE_NUMBER', [$data['phone']])
 			->addSubstitution( ':PLAN', [$data['plan']])
			
 			->setTemplateId("8fb37949-0893-4b04-906c-3b20dafa8821");

 		$response = $sendgrid->send($mail);
		
        return Response::success( [
            'message' => 'Message send, expect response soon'
        ] );

	}
	
	public function createSubscription( Request $request )
	{
        $data = $request->only( [
            'user_id', 'customerId', 'paymentMethodId', 'priceId'
        ] );
		
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
		
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
		
		try {
			
			$payment_method = $stripe->paymentMethods->retrieve( $data['paymentMethodId'] );
			// Attach the payment method to the customer
			
			$result = $payment_method->attach([
				'customer' => $data['customerId']
			]);	
				
			// Set the default payment method on the customer
			$result = $stripe->customers->update(
				$data['customerId'],
				[
					'invoice_settings' => [
						'default_payment_method' => $data['paymentMethodId'] 
					]
				]
			);
			
			

			// Create the subscription
			$subscription = $stripe->subscriptions->create([
				'customer' => $data['customerId'],
				'items' => [
					[ 
						'price' => $data['priceId'],
						'quantity' => 1,
					],
				],
			]);
			
			$result =  $subscription->jsonSerialize();
			
			
			
			BillingHistory::where('user_id', '=', $data['user_id'])->update([
				'status' => 1,
				'payment_agrement_id' => $result['id'], 
			]);
			
			User::where('user_id', '=', $data['user_id'])->update([
				'status' => 1
			]);
			
			// user::status = 0 : Invalid
			// user::status = 1 : Active (Email confirmed && Subscription)
			// user::status = 2 : Account Created (No Email confirmed  && No Subscription)
			// user::status = 3 : Account Deleted
			
			$user = User::where('user_id', '=', $data['user_id'])->first();
			
			$billing = BillingHistory::where('user_id', '=', $data['user_id'])->orderBy('created_at', 'desc')->first();
			
            $package_id = $billing->package_id;
			
			$package = BillingPackages::where('package_id', '=', $package_id)->first();
						
			$param = array(
				'name' => $user->first_name . ' ' . $user->last_name,
				'email' => $user->email,
				'phone' => $user->phone,
				'plan' => $package->name
			);
			
			$this->subscribed_notification($param);
			
			
			return json_encode($subscription);
			
		} catch (Exception $e) {
			$error =   array (
				'error' => array ( 'message'=> $e->getError()->message )
			);
			
			return  json_encode($error);
		}
		
	}
	
    public function subscribePayPal( $token, $package_id )
    {

        $user = UserTokens::checkToken($token);

        if(!$user)
            die('bad user');
//
//        \Auth::loginUsingId($user->user_id, TRUE);
//
//        $user = \Auth::getUser();


        $package = BillingPackages::where('package_id', '=', $package_id)->first();

        if(!$package)
            die('no package');

        return view('membership.package', [
            'package_description' => $package->description,
            'package_id' => $package->package_id,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'user_token' => $token
        ]);

    }

    public function subscribePending( Request $request )
    {

        $data = $request->only( [
            'user_token',
            'package_id'
        ] );

        $user = UserTokens::checkToken($data['user_token']);

        if(!$user)
            return Response::error( [
                'message' => 'Bad user'
            ] );

        $package = BillingPackages::where('package_id', '=', $data['package_id'])->first();

        if(!$package)
            return Response::error( [
                'message' => 'Bad package'
            ] );


//        $paypal = new PayPalService();
//        $paypal->cancelSubscription($user->user_id);


        if($package->type == BillingPackages::TYPE_FREE)
        {
            Billing::createNew( $user->user_id, $package->package_id );
            return Response::success([
                'message' => 'Subscription changed'
            ]);
        }
        
        
        $pending_id = Billing::createPending( $user->user_id, $package->package_id  );

        return Response::success( [
            'pending_id' => $pending_id
        ] );
        

    }

    public function subscribePendingWeb( Request $request )
    {

        $data = $request->only( [
            'package_id'
        ] );

        $user = \Auth::getUser();

        if(!$user)
            return Response::error( [
                'message' => 'Bad user'
            ] );


        $package = BillingPackages::where('package_id', '=', $data['package_id'])->first();

        if(!$package)
            return Response::error( [
                'message' => 'Bad package'
            ] );

        
//        $paypal = new PayPalService();
//        $paypal->cancelSubscription($user->user_id);
 
        if($package->type == BillingPackages::TYPE_FREE)
        {
            Billing::createNew( $user->user_id, $package->package_id );
            return Response::success([
                'message' => 'Subscription changed'
            ]);
        }


        $pending_id = Billing::createPending( $user->user_id, $package->package_id  );

        return Response::success( [
            'pending_id' => $pending_id
        ] );


    }
}
