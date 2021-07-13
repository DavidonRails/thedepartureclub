<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Billing;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{

    public function close() 
    {

        $user_id = \Auth::getUser()->user_id;

        User::where('user_id', '=', $user_id)->update([
            'status' => 3
        ]);

        \Auth::logout();
        \Cookie::queue(\Cookie::forget( 'token' ));

        $current_billing_package = Billing::where('user_id', '=', $user_id )->where( 'status', '=', Billing::STATUS_ACTIVE )->first();

        Billing::cancelSubscription( $current_billing_package->history_id );
        
        return Response::success([
            'message' => 'Account closed'
        ]);
        
    }


    public function password(Request $request)
    {

        $data = $request->only([
            'old_password',
            'new_password',
            'new_password_repeat'
        ]);

        $validate = $this->validatePasswordUpdate($data);

        if($validate->fails())
            return Response::error($validate->getMessageBag());

        $user_id = \Auth::getUser()->user_id;
        $email = \Auth::getUser()->email;

        $validate = \Auth::validate(['email' => $email, 'password' => $data['old_password']]);

        if($validate)
        {
            $data['password'] = $data['new_password'];
            User::updatePassword($user_id, $data);
            return Response::success(['message' => 'Password changed']);
        }
        else
        {
            return Response::error(['message' => 'Wrong password']);
        }

    }

    public function validatePasswordUpdate(array $data)
    {

        return \Validator::make($data, [
            'new_password' => 'required|min:6|max:30',
            'new_password_repeat' => 'required|same:new_password'
        ]);

    }
    
}
