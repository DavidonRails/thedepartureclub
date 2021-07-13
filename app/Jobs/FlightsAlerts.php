<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Flights;
use App\Services\NotificationsService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlightsAlerts extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $flights = new Flights();

        if(isset($this->data['flight_id']))
        {

            $flights_list = $flights->get([
                'admin' => TRUE,
                'origin_longitude' => $this->data['origin_longitude'],
                'origin_latitude' => $this->data['origin_latitude'],
                'destination_latitude' => $this->data['destination_latitude'],
                'destination_longitude' => $this->data['destination_longitude'],
                'flight_id' => $this->data['flight_id'],
                'type' => Flights::FLIGHT_TYPE_DEAL
            ]);


            if(!count($flights_list['data']))
                return TRUE;



            foreach ( $flights_list['data'] as $flight )
            {

                $notifications_service = new NotificationsService();
                $notifications_service->send($this->data['user_id'], 2, [
                    'flight_id' => $flight['flight_id'],
                    'origin' => $flight['route_origin'],
                    'destination' => $flight['route_destination'],
                    'message' => "Alert message"
                ], $flight['flight_id']);

            }

        }else
        {
            $flights_list = $flights->get([
                'origin_longitude' => $this->data['origin_longitude'],
                'origin_latitude' => $this->data['origin_latitude'],
                'destination_latitude' => $this->data['destination_latitude'],
                'destination_longitude' => $this->data['destination_longitude'],
                'type' => Flights::FLIGHT_TYPE_DEAL
            ]);
            if(!count($flights_list['data']))
                return TRUE;
            
            foreach ( $flights_list['data'] as $flight )
            {
    
                $notifications_service = new NotificationsService();
                $notifications_service->send($this->data['user_id'], 2, [
                    'flight_id' => $flight['flight_id'],
                    'origin' => $flight['route_origin'],
                    'destination' => $flight['route_destination'],
                    'message' => "Alert message"
                ], $flight['flight_id']);
                
            }

        }

    }
}
