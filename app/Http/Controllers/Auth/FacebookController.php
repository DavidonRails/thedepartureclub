<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\Mail;
use App\Models\Billing;
use App\Models\BillingPackages;
use App\Http\Response\Response;
use App\Models\Installations;
use App\Models\Pack;
use App\Models\PackUserRelations;
use App\Models\User;
use App\Models\UserFacebook;
use App\Models\UserTokens;
use Facebook\Facebook;
use Facebook\FacebookRequest;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacebookController extends Controller
{

    private $fb;

    public function __construct(  ) {

        $this->fb = new Facebook( [
            'app_id'                => env( 'FACEBOOK_APP_ID' ),
            'app_secret'            => env( 'FACEBOOK_APP_SECRET' ),
            'default_graph_version' => 'v3.0'
        ] );


    }

    protected function validateRegister(array $data)
    {
        Validator::extend('package', function($attribute, $value, $parameters)
        {
            $billing_packages = new BillingPackages();

            return $billing_packages->check($value);

        });

        return Validator::make($data, [
            'facebook_id' => 'required|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'tier' => 'required|package'
        ], ['package' => 'Please select package']);

    }

    public function fb(Request $request)
    {
        $data = [
            'token' => $request->token,
            'type' => $request->type,
            'tier' => (string)$request->tier,
            'device_id' => $request->device_id
        ];

        if($data['tier'] == '')
            $data['tier'] = 1;

        $login = $this->login($data);

        if($login)
        {
            $user = \Auth::user();

            $avatar =  $this->profileImage($user->avatar);
           

            $type = (isset($data->type) && in_array($data->type, ['web', 'ios', 'android'])) ? $data->type : '';

            $uuid = (string)\Webpatser\Uuid\Uuid::generate(1);

            $users_tokens = new UserTokens();
            $users_tokens->saveToken($user->user_id, $uuid, $type, TRUE);


            if(!empty($data['device_id']))
                Installations::connect($data['device_id'], $user->user_id);

            $pack_id = NULL;
            $pack = PackUserRelations::havePack($user->user_id);
            if(!$pack)
            {
                $pack_id = Pack::newPack('Hobo Squad', $user->user_id);
                PackUserRelations::connect($pack_id, $user->user_id, 1);
            }
            else
                $pack_id = $pack;


            $billing_pacakge_id = Billing::getUserPackage($user->user_id);
            
            return Response::success([
                    'user_id' => $user->user_id,
                    'user' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'avatar' => $avatar,
                        'pack_id' => $pack_id
                    ],
                    'pack_id' => $pack_id,
                    'token' => $uuid,
                    'facebook_token' => $login,
                    'active' => 1,
                    'billing_package_id' => $billing_pacakge_id
                ]);

        }
        else
        {
            $register = $this->register($data);

            if(isset($register['valid']) && $register['valid'] == FALSE)
                return Response::error($register['data']);

            if($register)
            {
                $user = \Auth::user();

                $avatar = NULL;

                $type = (isset($data->type) && in_array($data->type, ['web', 'ios', 'android'])) ? $data->type : '';

                $uuid = (string)\Webpatser\Uuid\Uuid::generate(1);

                $users_tokens = new UserTokens();
                $users_tokens->saveToken($user->user_id, $uuid, $type, TRUE);


                $pack_id = Pack::newPack('Hobo Squad', $user->user_id);
                PackUserRelations::connect($pack_id, $user->user_id, 1);

                if(!empty($data['device_id']))
                    Installations::connect($data['device_id'], $user->user_id);

                Installations::where('hash', '=', md5($request->device_id . $user->user_id))->update([
                    'status' => 1
                ]);

                $avatar = $this->profileImage($user->avatar);

                $billing_pacakge_id = Billing::getUserPackage($user->user_id);


                return Response::success([
                    'user_id' => $user->user_id,
                    'user' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'avatar' => $avatar,
                        'pack_id' => $pack_id
                    ],
                    'pack_id' => $pack_id,
                    'token' => $uuid,
                    'facebook_token' => $register,
                    'active' => 1,
                    'billing_package_id' => $billing_pacakge_id
                ]);

            }
        }
    }


    public function register($data)
    {

        $oAuth2Client = $this->fb->getOAuth2Client();

        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken( $data['token'] );

        $this->fb->setDefaultAccessToken( $longLivedAccessToken );

        try {
            $response = $this->fb->get( '/me?fields=first_name,last_name,email,picture.width(160).height(160)' );
            $user     = $response->getGraphUser();
        } catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
            return Response::error( 'facebook api exception' );
        } catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
            return Response::error( 'facebook sdk exception' );
        }

        $user = $user->all();

        if(!isset($user['id']))
            return  Response::error(['notification' => 'Error during registration. Please try later']);

        $data['first_name'] = $user['first_name'];
        $data['last_name'] = $user['last_name'];
        $data['email'] =  $user['email'];
        $data['facebook_id'] =  $user['id'];

        $validate = $this->validateRegister($data);

        if($validate->fails() == TRUE)
        {
            return [
                'valid' => FALSE,
                'data' => $validate->getMessageBag()
                ];
        }

        $password = str_shuffle(substr(md5(str_shuffle(date('l d F m Y r')) . rand()), 0, 8)); // znam da sam preterao :)
        $result = User::register( [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => $password,
            'confirmation_code' => '',
            'active' => 2,
            'type' => (in_array($data['type'], ['web', 'ios', 'android'])) ? $data['type'] : '',
            'tier' => $data['tier']
        ], $user['id']);

        if($result)
        {

            $user_fb = new UserFacebook();
            $user_fb->saveData($result->user_id, $data['facebook_id'], $longLivedAccessToken->getValue(), $data);

            $this->saveAvatar($response, $result->user_id);

            \Auth::loginUsingId($result->user_id, TRUE);

            $this->dispatch(new Mail([
                'type' => Mail::WELCOME_FACEBOOK,
                'data' => [
                    'email' => $result->email,
                    'password' => $password,
                ],
                'user' => [
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]
            ]));
            
            \Cookie::queue(\Cookie::forever('login-blog', 'pristup_blogu'));

            return  $longLivedAccessToken->getValue();
        }
        else
        {
            return  Response::error(['notification' => 'Error during registration']);

        }

    }

    public function login($data)
    {

        $this->fb->setDefaultAccessToken( $data['token'] );

        $oAuth2Client = $this->fb->getOAuth2Client();

        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken( $data['token'] );

        try {
            $response = $this->fb->get( '/me?fields=first_name,last_name,email,picture.width(160).height(160)' );
            $user_data = $response->getGraphUser();
        } catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
            return Response::error( 'facebook api exception' );
        } catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
            return Response::error( 'facebook sdk exception' );
        }



        $fb_user = $user_data->all();

        if(!isset($fb_user['id']))
            return  Response::error(['notification' => 'Error during login. Please try later']);

        $user = User::getByFacebookId($fb_user['id']);

        if($user)
        {

            if($user->status == 0)
            {

                \Alert::info('Your account is suspended, please contact support')->flash();
                return Response::error(['notification' => 'Your account is suspended, please contact support']);
            }

            $auth = \Auth::loginUsingId($user->user_id, TRUE);

            if($auth)
            {
                \Cookie::queue(\Cookie::forever('login-blog', 'pristup_blogu'));
                return $longLivedAccessToken->getValue();

            }
            else
            {
                return  Response::error(['notification' => 'Error during login']);

            }

        }
        else
        {
            return FALSE;
        }

    }

    public function saveAvatar($picture, $user_id)
    {

        $picture = $picture->getGraphObject();


        $is_silhouette = $picture->getProperty('picture')->getProperty('is_silhouette');
        $url = $picture->getProperty('picture')->getProperty('url');


        if(!$is_silhouette)
        {
            $image = file_get_contents($url);

            file_put_contents(base_path('storage/tmp/' . md5($user_id.'.facebook_image')), $image);

            $i = new \Imagick(base_path('storage/tmp/' . md5($user_id.'.facebook_image')));

            $i->setFormat('jpeg');

            $avatar = md5($user_id.time());
            file_put_contents(base_path('public/data/images/profile/' . $avatar . '.jpg'), $i);

            User::where('user_id', '=', $user_id)
                ->update([
                    'avatar' => $avatar
                ]);

            unlink(base_path('storage/tmp/' . md5($user_id.'.facebook_image')));
        }




    }

}
