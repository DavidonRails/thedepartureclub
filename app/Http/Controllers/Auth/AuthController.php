<?php

namespace App\Http\Controllers\Auth;

use App\Models\Billing;
use App\Models\BillingPackages;
use App\Http\Response\Response;
use App\Models\Installations;
use App\Models\Pack;
use App\Models\PackUserRelations;
use App\Models\User;
use App\Models\UserTokens;
use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Jobs\Mail;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'login', 'register', 'logOut', 'logOutWeb', 'token']]);
    }

    protected function validateRegister(array $data)
    {

        return Validator::make($data, [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:30',
        ]);

    }

    public function register(Request $request)
    {

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'avatar',
            'password',
            'type',
            'device_id'
        ]);

        $validate = $this->validateRegister($data);

        if($validate->fails())
        {
            return Response::error($validate->getMessageBag());
        }

        $confirmation_code = md5(str_random(32));

        $data['confirmation_code'] = $confirmation_code;

        $result = User::register($data);

        if($result)
        {

            $type = (in_array($request->type, ['web', 'ios', 'android'])) ? $request->type : '';


            $uuid = (string)\Webpatser\Uuid\Uuid::generate(1);

            $users_tokens = new UserTokens();
            $users_tokens->saveToken($result->user_id, $uuid, $type);

            if(!empty($data['device_id']))
                Installations::connect($data['device_id'], $result->user_id);

            Alert::info('Please confirm your email address')->flash();


            // TODO: 'Pack' is not being used. BillingPackages are now being used.
            $pack_id = Pack::newPack('Hobo Squad', $result->user_id);
            PackUserRelations::connect($pack_id, $result->user_id, 1);

            $this->dispatch(new Mail([
                'type' => Mail::WELCOME,
                'data' => [
                    'confirmation_code' => $confirmation_code,
                ],
                'user' => [
                    'email' => $result->email,
                    'first_name' => $result->first_name,
                    'last_name' => $result->last_name,
                ]
            ]));

            $billing_package_id = Billing::getUserPackage($result->user_id);

            $avatar = $this->profileImage($result->avatar);

            \Cookie::queue(\Cookie::forever('login-blog', 'pristup_blogu'));

            return Response::success([
                'user_id' => $result->user_id,
                'first_name' => $result->first_name,
                'last_name' => $result->last_name,
                'email' => $result->email,
                // 'phone' => $result->phone,
                // 'address' => $result->address,
                // 'hometown' => $result->city,
                'token' => $uuid,
                'avatar' => $avatar,
                'pack_id' => $pack_id,
                'active' => 2,
                'packages' => BillingPackages::getPackages(),
                'biling_pacakge_id' => $billing_package_id
            ]);
        }

    }

    public function passwordLogin(Request $request)
    {
        $password = $request->get('password', null);
        if(!is_null(\DB::table('password_login')->where('password', '=', md5($password))->first())) {
            \Cookie::queue('password-access', 'pristup_sajtu');
            return Response::success(['password' => true]);
        } else {
            return Response::error([
                'password' => 'Not correct password'
            ]);
        }
    }

    public function login(Request $request)
    {

        $check = User::where('email', '=', $request->email)->first(['status']);
        if(!$check)
            return Response::error([
                'email' => 'No user with ' . $request->email . ' email address'
            ]);


        if($check->status == 0)
        {
            \Alert::info('Your account is suspended, please contact support')->flash();
            return Response::error(['notification' => 'Your account is suspended, please contact support']);
        }


        if($check->status == 3)
        {
            \Alert::info('Your account is deleted, please contact support if you wont to activate account')->flash();
            return Response::error(['notification' => 'Your account is deleted, please contact support if you wont to activate account']);
        }

        $remember = FALSE;
        if($request->remember == 1)
            $remember = TRUE;

        $auth = \Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember);

//        $user = User::where('email', '=', $request->email)->where('password', '=', bcrypt($request->password))->first();

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
			
			
            $billing_pacakge_id = Billing::getUserPackage($user->user_id);
            
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
                    'biling_pacakge_id' => $billing_pacakge_id,
                ],
                'packages' => BillingPackages::getPackages(), // return all packages that has 0 custom  and 1 status 
                'token' => $uuid
            ]);


        }
        else
        {
            return  Response::error(['password' => 'Wrong password']);

        }


    }

    public function verify($confirmation_code)
    {

        if(!$confirmation_code)
        {
            die('no confirmation');
        }


        $user_model = new User();

        $user = $user_model->verify($confirmation_code);

        if($user == FALSE)
            return \Redirect::to('errors.bad_confirmation');


//        \Auth::loginUsingId($user->user_id, TRUE);

        return \Redirect::to('/');

    }

    public function logOutWeb()
    {
        $cookie = \Cookie::forget('login-blog');
        \Cookie::queue($cookie);

        $token = \Cookie::get('token');

        if($token)
        {
            $ut = new UserTokens();

            $ut->dumpToken( $token );

        }

        $cookie = \Cookie::forget( 'token' );

        \Cookie::queue($cookie);

        
        \Auth::logout();

        return \Redirect::to('/');

    }


    public function logOut(Request $request)
    {

        $data = $request->only(['token', 'device_id']);

        $user = UserTokens::where('token', '=', $data['token'])->get();

        if(!$user->count())
            return Response::error([
                'message' => 'Bad token'
            ]);

        $users_tokens = new UserTokens();
        $users_tokens->dumpToken($data['token']);
        
        Installations::where('hash', '=', md5($data['device_id'] . $user[0]->user_id))->update([
            'status' => 0
        ]);

        return Response::success(['message' => 'You are logedout from device']);

    }

    public function token( Request $request )
    {
        $token = $request->only(['token']);

        if(empty($token['token']))
            return Response::error(['message' => 'Not valid token']);

        $check = UserTokens::checkToken($token['token']);

        if($check)
            return Response::success(['message' => 'Valid']);
        else
            return Response::error(['message' => 'Not valid token']);



    }



}
