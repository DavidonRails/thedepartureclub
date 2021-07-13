<?php

namespace App\Http\Controllers\Pack;

use App\Http\Response\Response;
use App\Models\Pack;
use App\Models\PackUserRelations;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PackController extends Controller
{

    public function get()
    {

        $user_id = \Auth::getUser()->user_id;


        $pack_model = new Pack();

        $data = $pack_model->get($user_id);

        return Response::success($data);



    }

    public function edit( Request $request )
    {
        $data = $request->only(['name', 'pack_id']);

        $validate = $this->validateName($data);

        if($validate->fails())
        {
            return Response::error($validate->getMessageBag());
        }
        
        $edit = Pack::where('pack_id', '=', $data['pack_id'])->update([
            'name' => $data['name']
        ]);

        if($edit)
            return Response::success(['message' => 'Edit success']);
        else
            return Response::error(['message' => 'Please contact support']);
    }


    public function connect( Request $request )
    {

        $data = $request->only(['pack_id']);

        $valid = \Validator::make($data, [
            'pack_id' => 'required|exists:pack'
        ]);

        if($valid->fails())
            return Response::error($valid->getMessageBag());

        $user_id = \Auth::getUser()->user_id;

        if(!$user_id)
            return Response::error(['message' => 'Please contact support']);

        PackUserRelations::connect($data['pack_id'], $user_id);

        return Response::success([
            'message' => 'User added to pack'
        ]);

    }

    public function disconnect( Request $request )
    {
        $data = $request->only(['pack_id', 'user_id']);
        
        $valid = \Validator::make($data, [
            'pack_id' => 'required|exists:pack'
        ]);

        if($valid->fails())
            return Response::error($valid->getMessageBag());

        $user_id = $data['user_id'];

        if($user_id == \Auth::getUser()->user_id){
            return Response::error(['message' => 'Please contact support']);
        }

        if(!$user_id)
            return Response::error(['message' => 'Please contact support']);

        PackUserRelations::disconnect($data['pack_id'], $user_id);

        return Response::success([
            'message' => 'User removed from pack'
        ]);

    }

    public function validateName($data)
    {

        \Validator::extend('is_my', function($attribute, $value, $parameters)
        {
            return PackUserRelations::isMy($value);
        });

        return \Validator::make($data, [
            'name' => 'required|min:2|max:255',
            'pack_id' => 'required|is_my'
        ], ['is_my' => 'Please contact support']);
    }
}
