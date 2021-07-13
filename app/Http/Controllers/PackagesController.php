<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Billing;
use App\Models\BillingPackages;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PackagesController extends Controller
{

    public function setPackage( Request $request )
    {
        $only = $request->only(['package_id']);

        $user_id = \Auth::getUser()->user_id;

        Billing::createNew($user_id, $only['package_id']);
        
        return Response::success([
            'message' => 'Successful'
        ]);

    }
	
    public function updatePackage( Request $request )
    {
        $only = $request->only([
			'package_id', 'package_name', 'status', 'description', 'price', 'package_discount', 'stripe_price_id'
		]);
		
        $result  = BillingPackages::where('package_id', '=', $only['package_id'])->update([
			'name' => $only['package_name'],
			/* 'status' => $only['status'], */
			'description' => $only['description'],
			'price' => $only['price'],
			'discount' => $only['package_discount'],
			'stripe_price_id' => $only['stripe_price_id'],
		]);
		
        return Response::success([
            'message' => 'Successful'
        ]);

    }

    public function create( Request $request )
    {
        $only = $request->only(['package_name', 'price', 'package_discount', 'status', 'description', 'stripe_price_id']);
        BillingPackages::createNew($only['package_name'], $only['description'], $only['price'], $only['package_discount'], $only['stripe_price_id']);
        
        return Response::success([
            'message' => 'Successful'
        ]);

    }



    public function delete( $id )
    {
        BillingPackages::where('package_id','=',$id)->delete();
 
        return Response::success([
            'message' => 'Successful',
            //'package' => $package
        ]);

    }

    public function edit( $id )
    {
        $package = BillingPackages::where('package_id','=',$id)->get();
 
        return Response::success([
            'message' => 'Successful',
            'package' => $package
        ]);

    }



    public function getPackages()
    {

        $packages = BillingPackages::getPackages();
        
        return Response::success([
            'billing_packages' => $packages
        ]);

    }

    public function getUserPackage()
    {
        $user_id = \Auth::getUser()->user_id;

        $user_package = Billing::where('user_id', '=', $user_id)->where('status', '=', 1)->first([
            'package_id'
        ]);

        return Response::success([
            'package_id' => $user_package['package_id']
        ]);

    }


}
