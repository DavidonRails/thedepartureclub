<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\BillingPackages;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Prologue\Alerts\Facades\Alert;
use App\Models\UserTokens;
use App\Models\Installations;
use App\Models\Pack;
use App\Models\PackUserRelations;

class ContactController extends Controller
{

    public $emails = [];
    
    public function __construct(  ) 
    {

        if(env( 'CONTACT_EMAIL' ))
            $this->emails = explode(",", env( 'CONTACT_EMAIL' ));

    }

    public function contact( Request $request ) 
    {

        $data = $request->only( [
            'email',
            'name',
            'phone',
            'message'
        ] );


        $v = \Validator::make($data, [
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
            'message' => 'required'
        ]);

        if($v->fails())
            return Response::error( $v->getMessageBag() );


        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

        $html = "
                <p>
                    <b>Name: {$data['name']}</b>
                </p>
                <p>
                    <b>Email: {$data['email']}</b>
                </p>
                <p>
                    <b>Phone: {$data['phone']}</b>
                </p>
                <p>
                    <b>Message: {$data['message']}</b>
                </p>
            ";

        $mail
            ->addTo($this->emails)
            ->setFromName('Departure Club')
            ->setFrom('noreplay@thedepartureclub.com')
            ->setSubject('Departure Club Contact Form')
            ->setHtml($html);

        $response = $sendgrid->send($mail);


        return Response::success( [
            'message' => 'Message send, expect response soon'
        ] );

    }

    public function membership( Request $request )
    {

        $data = $request->only( [
            'user_id',
            'email',
            'message'
        ] );


        $v = \Validator::make($data, [
            'email' => 'required|email',
        ]);

        if($v->fails())
            return Response::error( $v->getMessageBag() );




        if($data['user_id'])
        {
            \Auth::loginUsingId( $data['user_id'], FALSE );

            $user = \Auth::getUser();

            $html = "
                <p>
                    <b>User ID: {$user->user_id}</b>
                </p>
                <p>
                    <b>Name: {$user->first_name} {$user->last_name}</b>
                </p>
                <p>
                    <b>Email: {$data['email']}</b>
                </p>
                <p>
                    <b>Message: {$data['message']}</b>
                </p>
            ";

        }
        else
        {
            $html= "
                <p>
                    <b>Email: {$data['email']}</b>
                </p>
                <p>
                    <b>Interested in Departure Club pro membership</b>
                </p>
            ";
        }
        $subject = 'Departure Club Membership Form';

        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

        $mail
            ->addTo($this->emails)
            ->setFromName($subject)
            ->setFrom('noreplay@thedepartureclub.com')
            ->setSubject('Membership pro')
            ->setHtml($html);

        $response = $sendgrid->send($mail);


        return Response::success( [
            'message' => 'Message send, expect response soon'
        ] );

    }
	
	public function join_notification($data) {
		/*
		// Using Laravel Mail Function
		
		\Mail::send('email.join_notification', $data, function($message) use($data){
			$message
				->from('noreplay@thedepartureclub.com')
				->to( 'josh@thedepartureclub.com' )
				->cc('sshere@exqsd.com')
				->subject("New User created!");
		});
		*/
		
        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

		$emails = ["memberships@thedepartureclub.com"];

 		$mail
			/*
			->addTo('sshere@exqsd.com')
 			->addTo('devischan123@gmail.com')

			->addTo('sshere+1@exqsd.com')
			->addTo('josh@thedepartureclub.com')
			*/
			->setTos($emails)
 			->setFromName(" ")
 			->addHeader("TDC", "1")
 			->setFrom('noreply@thedepartureclub.com')
 			->setSubject('Admin Notification - New user has joined.')
 			->setHtml(' ')
 			
 			->addSubstitution( ':DATE_SEND:', [date("F j, Y, g:i a")])
 			->addSubstitution( ':NAME:', [$data['name']])
 			->addSubstitution( ':EMAIL:', [$data['email']])
 			->addSubstitution( ':PHONE_NUMBER:', [$data['phone']])
 			->addSubstitution( ':PLAN:', [$data['plan']])
			
 			->setTemplateId("9ce68de9-2800-4bbf-a9c7-a2c0cee98774");

 		$response = $sendgrid->send($mail);
		
        return Response::success( [
            'message' => 'Message send, expect response soon'
        ] );
	}

    public function applyMembership(Request $request)
    {
		
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'password',
            'package_id'
        ]);

        $v = \Validator::make($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'phone' => 'sometimes|numeric',
        ]);

        if($v->fails())
            return Response::error( $v->getMessageBag() );
    
		
        try
        {
            $user = new User();
            $user->password = Hash::make($data['password']);
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->save();
			
			
            $bh = new BillingHistory();
            $bh->user_id = $user->user_id;
            $bh->package_id = $data['package_id'];
            $bh->status = 0;
			$bh->save();
			
        }

        catch(\Illuminate\Database\QueryException $e)
        {
			
            $error = $e->getMessage();
			
            if(strpos($error, 'users_email_unique') !== -1) return Response::error( ["email"=>"The email address already exists."] );
            
        }
		
		$package = BillingPackages::where('package_id', '=', $data['package_id'])->first();
		
		$param = array(
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
			'phone' => $data['phone'],
            'plan' => $package->name
        );
		
		$this->join_notification($param);
		
        $auth = Auth::attempt(['email' => $user->email, 'password' => $data['password'] ]);

        if($auth)
        {
            $user = \Auth::user();

            if($user->status == 2)
                Alert::info('Please confirm your email address')->flash();

            $type = (in_array($request->type, ['web', 'ios', 'android'])) ? $request->type : '';


            $uuid = (string)\Webpatser\Uuid\Uuid::generate(1);

            $users_tokens = new UserTokens();
            $users_tokens->saveToken($user->user_id, $uuid, $type);

            if(!empty($request->device_id))
            {
                Installations::connect($request->device_id, $user->user_id);
            }

            $avatar = $this->profileImage($user->avatar);



            $pack_id = NULL;
            $pack = PackUserRelations::havePack($user->user_id);
            if(!$pack)
            {
                $pack_id = Pack::newPack('Hobo Squad', $user->user_id);
                PackUserRelations::connect($pack_id, $user->user_id, 1);
            }
            else
                $pack_id = $pack;


            Installations::where('hash', '=', md5($request->device_id . $user->user_id))->update([
                'status' => 1
            ]);
			
			// Important : When user has no any billing_history, system create new billing with package that has 0 as price and return created billing_package_id 
			// Other way : System create new billing_history with (1 as STATUS) by ($0 as package price) through 1st login of new user
			
			
            //$billing_pacakge_id = Billing::getUserPackage($user->user_id);
            
            \Cookie::queue(\Cookie::forever('login-blog', 'pristup_blogu'));

            return Response::success([
                'user' => [
                    'user_id' => $user->user_id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
					'last_name' => $user->last_name,
					'email' => $user->email,
					'role' => $user->role,
					'phone' => $user->phone,
					'address' => $user->address,
                    'active' => $user->status,
                    'avatar' => $avatar,
                    'hometown' => $user->city,
                    'pack_id' => $pack_id,
                    //'biling_pacakge_id' => $billing_pacakge_id,
                ],
                'packages' => BillingPackages::getPackages(), // return all packages that has 0 custom  and 1 status 
                'token' => $uuid
            ]);
        }

        return Response::error( [
            'other' => 'Account could not be created.'
        ] );
    }
	
	public function getMembership(Request $request)
    {
        $data = $request->only([
            'user_id',
        ]);
		
		$bh = BillingHistory::where('user_id', '=', $data['user_id'])->where('status', '=', 1)->get();
		
		return Response::success($bh);
	}
    
}
