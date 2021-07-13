<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\User;
use App\Models\UserTokens;
use Illuminate\Http\Request;
use App\Http\Requests;
use Intervention\Image\Facades\Image;

class UploadsController extends Controller
{
    public function upload(Request $request)
    {


        if($request->hasFile('file'))
        {

            switch($request->get('type'))
            {
                case 'avatar':

                    return $this->uploadAvatar($request);


                    break;
            }

        }

    }
    public function uploadAvatar(Request $request)
    {

        $img = Image::make($request->file('file')->getPathname());

        $img->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->crop(100, 100);

        $user_id = \Auth::getUser()->user_id;

        $avatar = md5($user_id.time());
        $image = base_path() . '/public/data/images/profile/' . $avatar . '.jpg';
        User::where('user_id', '=', $user_id)->update([
            'avatar' => $avatar,
        ]);

        $img->save($image);

        return Response::success(['message' => 'Image uploaded', 'image' => $this->profileImage($avatar)]);


    }

    public function uploadAvatarMobile(Request $request, $token)
    {
        $user = UserTokens::checkToken($token);

        if(!$user)
            return Response::success('Bad token');

        $img = Image::make($request->file('imageParamName')->getPathname());

//        $img->resize(100, null, function ($constraint) {
//            $constraint->aspectRatio();
//        });
//        $img->crop(100, 100);

        $user_id = $user->user_id;
        $avatar = md5($user_id.time());
        $image = base_path() . '/public/data/images/profile/' . $avatar . '.jpg';
        User::where('user_id', '=', $user_id)->update([
            'avatar' => $avatar,
        ]);
        
        $img->save($image);

        return Response::success(['message' => 'Image uploaded', 'image' => $this->profileImage($avatar)]);

    }
}
