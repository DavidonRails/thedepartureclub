<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Jobs\Mail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

class ProfileController extends Controller
{

    public function __construct()
    {
        view()->share('user', $this->getUser());
    }


    public function index()
    {

        $user_id = \Auth::getUser()->user_id;

        $users = new User();

        $user = $users->getById($user_id)->toArray();

        return view('profile.profile', [
            'data' => $user
        ]);
    }


    public function updateProfile(Request $request)
    {

        $user = \Auth::getUser();

        if(!$user)
            return Response::error('Security issue');

        $data = $request->only([
            'email',
            'first_name',
            'last_name',
            'company',
            'hometown'
        ]);

        $email_change = FALSE;
        if($user->email != $data['email'])
            $email_change = TRUE;

        $validate = $this->validateProfile($data, $email_change);

        if($validate->fails())
            return Response::error($validate->getMessageBag());

        $data['confirmation_code'] = md5( rand( 1000, 9999 ) . str_shuffle( $data['email'] . date( 'r' ) ) );

        $result = User::updateProfile($user->user_id, $data, $email_change);

        if($result)
        {

            if($email_change)
                $this->dispatch(new Mail([
                    'type' => Mail::EMAIL_CHANGE,
                    'data' => [
                        'confirmation_code' => $data['confirmation_code'],
                        'new_email' => $data['email']
                    ],
                    'user' => [
                        'email' => $data['email'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name']
                    ]
                ]));
            

        }

        if($result)
            return Response::success('Profile updated');

    }

    public function updatePassword(Request $request)
    {

        $user = \Auth::getUser();

        if(!$user)
            return Response::error('Security issue');

        $data = $request->only([
            'password',
            'repeat_password'
        ]);


        $validate = $this->validatePasswordUpdate($data);

        if($validate->fails())
            return Response::error($validate->getMessageBag());

        $result = User::updatePassword($user->user_id, $data);

        if($result)
            return Response::success('Password updated');

    }


    public function validateProfile(array $data, $email)
    {
        $validate = [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255'
        ];

        if($email)
            $validate['email'] = 'required|email|max:255|unique:users';

        return Validator::make($data, $validate);

    }

    public function validatePasswordUpdate(array $data)
    {

        return Validator::make($data, [
            'password' => 'required|min:6|max:30',
            'repeat_password' => 'required|same:password'
        ]);

    }

}
