<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{

    public function verify( $confirmation_code )
    {
        if(strlen($confirmation_code) != 32)
        {
            return \Redirect::to('errors.bad_confirmation');

        }

        $user_model = new User();

        $user = $user_model->verifyPending($confirmation_code);

        if($user == FALSE)
            return \Redirect::to('errors.bad_confirmation');

        return \Redirect::to('');
    }

}
