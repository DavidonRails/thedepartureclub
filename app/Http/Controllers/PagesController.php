<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Alerts;
use App\Models\Events;
use App\Models\EventsImages;
use App\Models\User;
use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\BillingPackages;
use App\Models\Bookings;
use App\Models\Flights;
use App\Models\Pack;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{

    public function __construct()
    {
		view()->share('user', $this->getUser());
		
    }


    public function events()
    {
        $events = new Events();
        $events_images = new EventsImages();

		$events_all = $events->get();
		
		$result = [];
		
		foreach($events_all as &$event) {
            if(!empty($event->event_image_id)) {
				
                $event_image = EventsImages::find($event->event_image_id);
                $event['event_image'] = $event_image->path . $event_image->name;
            }
			
			$date = date_create($event['event_at']);
			
			$event['event_start_date'] =  date_format($date, 'l,  F, jS, Y');
			$event['event_start_time'] = date_format($date, 'g:ia');

			$date = date_create($event['event_ends_at']);
			
			$event['event_end_date'] =  date_format($date, 'l,  F, jS, Y');
			$event['event_end_time'] = date_format($date, 'g:ia');

			array_push($result, $event);
		}
        
        return view('events', [
            'events' => $result
        ]);
    }
    

    public function eventDetails($event_id)
    {
        $events = new Events();
        $event_details = Events::find($event_id);

		if(!empty($event_details->event_image_id)) {
			
			$event_image = EventsImages::find($event_details->event_image_id);
			$event_details['event_image'] = $event_image->path . $event_image->name;
		}
		
		if(empty($event_details['event_image'])) {
			$event_details['event_image'] = 'https://www.ochch.org/wp-content/themes/mast/images/empty-photo.jpg';
		}

		
		$date = date_create($event_details['event_at']);
		
		$event_details['event_start_date'] =  date_format($date, 'l,  F, jS, Y');
		$event_details['event_start_time'] = date_format($date, 'g:ia');
		$event_details['event_start_month'] = date_format($date, "M");
		$event_details['event_start_day'] = date_format($date, "d");

		$date = date_create($event_details['event_ends_at']);
		
		$event_details['event_end_date'] =  date_format($date,'l,  F, jS, Y');
		$event_details['event_end_time'] = date_format($date,'g:ia');
		$event_details['event_end_month'] = date_format($date, "M");
		$event_details['event_end_day'] = date_format($date, "d");

        return view('event-details', [
            'event' => $event_details,
        ]);
    }
    

    public function charterFlight()
    {
        return view('charter-flight');
    }

    public function selectAircraft()
    {
        return view('select-aircraft');
    }

    public function faq()
    {
        return view('faq');
    }

    public function contactPage()
    {
        return view('contact');
    }

    public function submited()
    {
        return view('request-submited');
    }

    public function termsConditions()
    {
        return view('auth/terms-conditions');
    }

    public function termsConditionsIOS()
    {
        return view('auth/terms-conditions-ios');
    }


    /////// IMPLEMENTED ///////


    public function index()
    {
        $discount = 0;
        $user = \Auth::getUser();
        if($user != null) {
            $user_id = $user->user_id;
            $user_model = new User();
            $package = $user_model->getByIdWithPackage($user_id);
            $discount = $package->discount;
        }
 
        $filter = [
            'status' => Flights::FLIGHT_STATUS_ACTIVE,
            'type' => Flights::FLIGHT_TYPE_DEAL
        ];

        $flights = new Flights();

        $flights_data = $flights->get($filter, TRUE, 6);
	
        $packages_model = new BillingPackages();


        $packages = $packages_model->get();


        return view('index', [
            'flights' => $flights_data['data'],
            'discount' => $discount,
            'packages' => $packages
        ]);

    }

    public function passwordLogin()
    {
        $cookie = \Cookie::get('password-access');
        if ($cookie) {
            return redirect()->guest('/');
        }
        return view('auth/password');
    }

    public function flights(Request $request)
    {
        $discount = 0;
        $user = \Auth::getUser();
        if($user != null) {
            $user_id = $user->user_id;
            $user_model = new User();
            $package = $user_model->getByIdWithPackage($user_id);
            $discount = $package->discount;
        }

        $filter = [
            'status' => Flights::FLIGHT_STATUS_ACTIVE,
            'type' => Flights::FLIGHT_TYPE_DEAL
        ];

        $search = [];

        if($request->isMethod('post'))
        {

            $data = $request->only(['origin_location_input', 'destination_location_input', 'price_from', 'price_to']);

            if(!empty($data['origin_location_input']))
            {

                if(substr( $data['origin_location_input'], 0, 1) != '{' )
                {

                    $data['origin_location_input'] = $this->searchCity( $data['origin_location_input'] );

                    $filter['origin_latitude'] = $data['origin_location_input']['geometry']['lat'];
                    $filter['origin_longitude'] = $data['origin_location_input']['geometry']['lng'];
                }
                else
                {
                    $data['origin_location_input'] = json_decode($data['origin_location_input']);

                    $filter['origin_latitude'] = $data['origin_location_input']->geometry->lat;
                    $filter['origin_longitude'] = $data['origin_location_input']->geometry->lng;
                }

                $search['origin_location_input'] = (array)$data['origin_location_input'];

            }

            if(!empty($data['destination_location_input']))
            {
                if(substr( $data['destination_location_input'], 0, 1) != '{')
                {

                    $data['destination_location_input'] = $this->searchCity( $data['destination_location_input'] );

                    $filter['destination_latitude'] = $data['destination_location_input']['geometry']['lat'];
                    $filter['destination_longitude'] = $data['destination_location_input']['geometry']['lng'];
                }
                else
                {
                    $data['destination_location_input'] = json_decode($data['destination_location_input']);
                    $filter['destination_latitude'] = $data['destination_location_input']->geometry->lat;
                    $filter['destination_longitude'] = $data['destination_location_input']->geometry->lng;
                }

                $search['destination_location_input'] = (array)$data['destination_location_input'];

            }


            $filter['price_from'] = $data['price_from'];
            $filter['price_to'] = $data['price_to'];
        }

        $flights = new Flights();

        $flights_data = $flights->get($filter, FALSE, 6);

        return view('book-flight', [
            'flights' => $flights_data,
            'search' => (array)$search,
            'discount' => $discount
        ]);

    }




    public function flightStatus()
    {

        $booking_model = new Bookings();

        $user_id = \Auth::getUser()->user_id;


        $my_bookings = $booking_model->getBookings($user_id, [], FALSE);

        return view('flight-status', [
            'bookings' => $my_bookings
        ]);
    }



    public function flightsAlerts()
    {

        $user_id = \Auth::getUser()->user_id;

        $alerts_model = new Alerts();

        $alerts = $alerts_model->get( $user_id );

        return view('flightsalerts', [
            'alerts' => $alerts
        ]);
    }
	
	public function stripeWebhook(Request $request) 
	{
		
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

/*
{
  "created": 1326853478,
  "livemode": false,
  "id": "evt_00000000000000",
  "type": "customer.subscription.pending_update_expired",
  "object": "event",
  "request": null,
  "pending_webhooks": 1,
  "api_version": null,
  "data": {
    "object": {
      "id": "sub_00000000000000",
      "object": "subscription",
      "application_fee_percent": null,
      "billing_cycle_anchor": 1592316117,
      "billing_thresholds": null,
      "cancel_at": null,
      "cancel_at_period_end": false,
      "canceled_at": null,
      "collection_method": "charge_automatically",
      "created": 1592316117,
      "current_period_end": 1594908117,
      "current_period_start": 1592316117,
      "customer": "cus_00000000000000",
      "days_until_due": null,
      "default_payment_method": null,
      "default_source": null,
      "default_tax_rates": [
      ],
      "discount": null,
      "ended_at": null,
      "items": {
        "object": "list",
        "data": [
          {
            "id": "si_00000000000000",
            "object": "subscription_item",
            "billing_thresholds": null,
            "created": 1592316117,
            "metadata": {
            },
            "plan": {
              "id": "price_00000000000000",
              "object": "plan",
              "active": true,
              "aggregate_usage": null,
              "amount": 500000,
              "amount_decimal": "500000",
              "billing_scheme": "per_unit",
              "created": 1592315572,
              "currency": "usd",
              "interval": "month",
              "interval_count": 1,
              "livemode": false,
              "metadata": {
              },
              "nickname": "$5000 per month membership fee Details will be provided in a description field .... 50% discount flights for the whole plane",
              "product": "prod_00000000000000",
              "tiers": null,
              "tiers_mode": null,
              "transform_usage": null,
              "trial_period_days": null,
              "usage_type": "licensed"
            },
            "price": {
              "id": "price_00000000000000",
              "object": "price",
              "active": true,
              "billing_scheme": "per_unit",
              "created": 1592315572,
              "currency": "usd",
              "livemode": false,
              "lookup_key": null,
              "metadata": {
              },
              "nickname": "$5000 per month membership fee Details will be provided in a description field .... 50% discount flights for the whole plane",
              "product": "prod_00000000000000",
              "recurring": {
                "aggregate_usage": null,
                "interval": "month",
                "interval_count": 1,
                "usage_type": "licensed"
              },
              "tiers_mode": null,
              "transform_quantity": null,
              "type": "recurring",
              "unit_amount": 500000,
              "unit_amount_decimal": "500000"
            },
            "quantity": 1,
            "subscription": "sub_00000000000000",
            "tax_rates": [
            ]
          }
        ],
        "has_more": false,
        "url": "/v1/subscription_items?subscription=sub_HTcb82CaVzj00Y"
      },
      "latest_invoice": "in_1GufMPB8H3AkX9IhiSAHNsV0",
      "livemode": false,
      "metadata": {
      },
      "next_pending_invoice_item_invoice": null,
      "pause_collection": null,
      "pending_invoice_item_interval": null,
      "pending_setup_intent": null,
      "pending_update": null,
      "plan": {
        "id": "price_00000000000000",
        "object": "plan",
        "active": true,
        "aggregate_usage": null,
        "amount": 500000,
        "amount_decimal": "500000",
        "billing_scheme": "per_unit",
        "created": 1592315572,
        "currency": "usd",
        "interval": "month",
        "interval_count": 1,
        "livemode": false,
        "metadata": {
        },
        "nickname": "$5000 per month membership fee Details will be provided in a description field .... 50% discount flights for the whole plane",
        "product": "prod_00000000000000",
        "tiers": null,
        "tiers_mode": null,
        "transform_usage": null,
        "trial_period_days": null,
        "usage_type": "licensed"
      },
      "quantity": 1,
      "schedule": null,
      "start_date": 1592316117,
      "status": "active",
      "tax_percent": null,
      "transfer_data": null,
      "trial_end": null,
      "trial_start": null
    }
  }
}
*/
		$event_type = $request['type'];
		$customer_id = $request['data']['object']['customer'];
		
		// Occurs three days before a subscription's trial period is scheduled to end, or when a trial is ended immediately (using trial_end=now).
		if ($event_type == 'customer.subscription.trial_will_end') {
			$user = User::where('customer_id', '=', $customer_id)->first();
			
			if( $user['user_id'] )
			{
				/*
				$billing_history = new BillingHistory();
				$result = $billing_history->deactive( $user['user_id'] );
				*/
				$result = BillingHistory::where('user_id', '=', $user['user_id'])->where('status', '=', 1)->update([
					'status' => 0,
					'payment_agrement_id' => ''
				]);
				return $result;
			}
		}
		
		return 'TheDepartureClub hook - ok';
	}

    public function membership()
    {		
        $packages_model = new BillingPackages();
        $packages = $packages_model->get();

        return view('membership',
        [
			'packages' => $packages
		]);
    }



    public function hoboSquad()
    {
        $user_id = \Auth::getUser()->user_id;

        $pack = new Pack();

        $data = $pack->get( $user_id );

        $user = \Auth::getUser()->toArray();
        $user['avatar'] = $this->profileImage( $user['avatar'] );
        return view('hobo-squad', [
            'pack' => $data,
            'me' => $user
        ]);
    }



    public function flightDetails($flight_id)
    {

        $flights_model = new Flights();
        $flight_details = $flights_model->getById( $flight_id );


        if(!$flight_details)
            die('404');


        $flight_details['flight_id'] = $flight_id;

        return view('flight-details', $flight_details);
    }



    public function mailchimpSubscribe(Request $request)
    {

        $data = $request->only( [
            'email',
            'name',
            'state'
        ] );


        if(!filter_var( $data['email'], FILTER_VALIDATE_EMAIL ))
            return Response::error( [
                'message' => 'Please enter valid email'
            ] );

        $data['status'] = 'subscribed';
        
//        $data = [
//            'email'     => 'johndoe@example.com',
//            'status'    => 'subscribed',
//            'firstname' => 'john',
//            'lastname'  => 'doe'
//        ];


        if(empty( $data['state'] ))
            unset( $data['state'] );

        if(empty( $data['name'] ))
            unset( $data['name'] );

        $merge_fields = [];
        foreach ($data as $k => $v)
        {
            $merge_fields[strtoupper( $k )] = $v;
        }
        unset( $merge_fields['email'] );
        $apiKey = 'b540d57f98bfaaf745efc1c622a1a211-us3';
        $listId = '4055d06315';

        $memberId = md5(strtolower($data['email']));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        $json = json_encode([
            'email_address' => $data['email'],
            'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending".
            'merge_fields' => $merge_fields
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

//            return $httpCode;

        return Response::success( [
            'message' => 'Subscribed'
        ] );


    }



}
