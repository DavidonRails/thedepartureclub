<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Jobs\FlightsAlerts;
use App\Models\Alerts;
use Illuminate\Http\Request;

class AlertsController extends Controller
{

    public $alerts = NULL;

    public function __construct()
    {
        $this->alerts = new Alerts();
    }

    public function get()
    {

        $user_id = \Auth::getUser()->user_id;

        $data = $this->alerts->get($user_id);

        return Response::success($data);


    }

    public function add(Request $request)
    {
        $data = $request->only([
            'origin',
            'origin_longitude',
            'origin_latitude',
            'destination',
            'destination_longitude',
            'destination_latitude'
        ]);

        $validate = $this->validateAlert($data);

        if($validate->fails())
            return Response::error($validate->getMessageBag());
        $user_id = \Auth::getUser()->user_id;

        $result = $this->alerts->add($user_id, $data);
        
        if($result)
            return Response::success([
                'message' => 'Alert added',
                'alert_id' => $result
            ]);
        else
            return Response::error('Error while adding alert. Please contact support');

    }

    public function del(Request $request)
    {
        $user_id = \Auth::getUser()->user_id;

        $data = $request->only([
            'alert_id'
        ]);

        $result = $this->alerts->del($user_id, $data['alert_id']);

        if($result)
            return Response::success('Alert deleted');
        else
            return Response::error('Error while deleting alert. Please contact support');
    }

    public function validateAlert( array $data )
    {

        return \Validator::make($data, [
            'origin' => 'required|min:3|max:250',
            'origin_longitude' => 'required|max:250',
            'origin_latitude' => 'required|max:250',
            'destination' => 'required|min:3|max:250',
            'destination_longitude' => 'required|max:250',
            'destination_latitude' => 'required|max:250'
        ]);

    }

}
