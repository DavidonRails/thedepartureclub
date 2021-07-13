<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Response\Response;


class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $old_password = $request->get('old_password', '');
        $new_password = $request->get('new_password', '');

        try {
           if(\DB::table('password_login')->where('password', '=', md5($old_password))->update(['password' => md5($new_password)])) {
               return Response::success(['old_password' => 'Successfully changed password']);
           }
           return Response::error(['old_password' => 'Wrong password']);
        } catch(\Exception $e) {
            return Response::error(['old_password' => $e->getMessage()]);
        }
    }

}