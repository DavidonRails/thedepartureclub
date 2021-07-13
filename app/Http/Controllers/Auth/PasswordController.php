<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Response\Response;
use App\Jobs\Mail;
use App\Models\User;
use App\Models\UsersPasswordResets;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function reset(Request $request)
    {

        $only = $request->only(['email']);

        $email = User::getByEmail($only['email']);

        if($email) {

            $check_email = \Validator::make($only, [
                'email' => 'email'
            ]);

            if ($check_email->fails())
                return Response::error($check_email->getMessageBag());

            $this->validate($request, ['email' => 'required|email']);


            $token = str_shuffle(md5($only['email']) . str_random()) . str_random();

            UsersPasswordResets::create([
                'email' => $only['email'],
                'token' => $token
            ]);


            $this->dispatch(new Mail([
                'type' => Mail::PASSWORD_RESET,
                'data' => [
                    'confirmation_code' => $token
                ],
                'user' => [
                    'email' => $only['email'],
                    'first_name' => '',
                    'last_name' => ''
                ]
            ]));

            return Response::success('Please check email');
        } else {
            return Response::error("We don't have user with this email");
        }

    }

    public function postReset( Request $request )
    {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);


        $credentials = $request->only(
            'password', 'password_confirmation', 'token'
        );

        $email = User::getEmailFromToken($credentials['token']);

        if ($email) {
            $credentials['email'] = $email->email;

            $user = User::getByEmail($email->email);

            $user->password = bcrypt($credentials['password']);

            try {
                $user->save();
                return redirect(url('/'));
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => $e->getMessage()]);
            }
        } else {
            return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => "We can't find user with this email"]);
        }
    }

}
