<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Installations;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class InstallationController extends Controller
{

    public function installation(Request $request)
    {

        $only = $request->only(['push_token', 'device_id']);

        $installations = new Installations();

        $result = $installations->installation($only['push_token'], $only['device_id']);

        if($result)
            return Response::success([
                'message' => 'Saved'
            ]);
        else
            return Response::error([
                'message' => 'Please contact support'
            ]);

    }

}
