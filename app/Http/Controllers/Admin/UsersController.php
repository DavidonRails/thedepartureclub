<?php

namespace App\Http\Controllers\Admin;

use App\Http\Response\Response;
use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\BillingPackages;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function create(Request $request)
    {
        $data = [
                'email' => $request->get('email'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'password' => $request->get('password'),
                'type' => 'web',
            ];
        $validate = $this->validateRegister($data);

        if($validate->fails())
        {
            return Response::error($validate->getMessageBag());
        }
        $data['password'] = bcrypt($request->get('password'));
        try {
            $user = User::create($data);

            Billing::getUserPackage($user->user_id);

            return Response::success(['create_user' => 'Successfully changed password']);

        } catch (\Exception $e) {
            return Response::error(['create_user' => $e->getMessage()]);
        }


    }

    public function delete($user_id)
    {
        try{
            User::find($user_id)->delete();
            return Response::success(['delete_user' => 'Successfully deleted user']);
        } catch (\Exception $e) {
            return Response::error(['delete_user' => $e->getMessage()]);
        }
    }

    public function get(Request $request)
    {
        $filter = $request->only([
            'status'
        ]);

        if($filter['status'] === 'all')
            unset($filter['status']);
        

        $users = new User();

        $users_list = $users->getAll($filter);


        return Response::success($users_list);


    }

    public function user($user_id)
    {

        $billingHistory = BillingHistory::where('user_id', '=', $user_id)->get()->first();
        $billingPackage = BillingPackages::where('package_id', '=', $billingHistory->package_id)->get()->first();

        $user = User::where('user_id', '=', $user_id)->first([
            'email',
            'address',
            'avatar',
            'city',
            'company',
            'country',
            'first_name',
            'last_name',
            'phone',
            'status',
            'created_at',
            'role'
        ])->toArray();


        return Response::success(['user' => $user, 'package' => $billingPackage]);



    }


    public function edit( Request $request ) 
    {
    
        $data = $request->only([
            'status',
            'role',
	    'package',
	    'email',
            'first_name',
            'last_name',
        ]);

        $validate = $this->validateEdit($data);

        if($validate->fails())
        {
            return Response::error($validate->getMessageBag());
        }

	$user_id = $request->get('user_id');
        $user = User::find($user_id);
	$billingHistory = BillingHistory::where('user_id', '=', $user_id)->get()->first();
	$billingHistory->package_id = $request->get('package');
        $billingHistory->save();

        try {
            $user->update($data);
        } catch (\Exception $e) {
            return Response::error(['edit_user' => 'Unable to edit user']);
	}




        if(!empty($request->get('password'))) {
            $user->update(['password' => bcrypt($request->get('password'))]);
        }

        
        return Response::success([
            'message' => 'User edited'
        ]);
            
    }

    public function getOperators()
    {

        $operators = User::where('role', '=', 'operator')->where('status', '=', 1)->get();

        if($operators)
        {
            $operators = $operators->toArray();
            foreach ( $operators as $key => $operator )
            {
                $operators[$key]['name'] = $operator['first_name'] . ' ' . $operator['last_name'];
            }
        }
        else
            $operators = [];

        return Response::success($operators);

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
    protected function validateEdit(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|',
        ]);
    }
}
