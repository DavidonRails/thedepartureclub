<?php

namespace App\Http\Controllers;
use Mail;
use App\Http\Response\Response;
use App\Models\Events;
use App\Models\EventsImages;
use App\Models\BillingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\ReservationEmail;



class EventsController extends Controller
{
    public $events = NULL;

    public function __construct()
    {
        $this->events = new Events();
    }

	
	public function getAll( )
	{
		$events = new Events();

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

		return Response::success($events_all);

	}
	
	public function reservation( Request $request )
	{
		
		$data = $request->only([
			'event_id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'seats_count'
		]);
		
		$receiver_name = $data['first_name'] . ' ' . $data['last_name'];
		
		$e =  Events::find($data['event_id']);
		
		$date = date_create($e['event_at']);
		
		$e['event_start_date'] =  date_format($date, 'l,  F, jS, Y');
		$e['event_start_time'] = date_format($date, 'g:ia');

		$date = date_create($e['event_ends_at']);
		
		$e['event_end_date'] =  date_format($date, 'l,  F, jS, Y');
		$e['event_end_time'] = date_format($date, 'g:ia');

		
		$event_duration = $e['event_start_date'] . ' from ' . $e['event_start_time'] . ' to ' . $e['event_end_time'];

		$start_date_time = date_create($e['event_at']);
		$start_date_time = date_format($start_date_time,"Ymd\THis");
		
		$end_date_time = date_create($e['event_ends_at']);
		$end_date_time = date_format($end_date_time,"Ymd\THis");
		
		$current_date_time = date_format(date_create(),"Ymd\THis");
		
		$dtstamp = date_create($e['created_at']);
		$dtstamp = date_format($dtstamp,"Ymd\THis");
		
		$modified = date_create($e['updated_at']);
		$modified = date_format($modified,"Ymd\THis");
		
		

		
 		$event_name = $e['name'];
		
		
		
		//$start_date_time->setTimezone(new DateTimeZone('America/Toronto'));
/*	
 		$contents = "BEGIN:VCALENDAR
						BEGIN:VEVENT
						CREATED:20200619T020000Z
						DTEND;TZID=America/Toronto:20200622T110000
						DTSTAMP:20200619T020000Z
						DTSTART;TZID=America/Toronto:20200622T180000
						LAST-MODIFIED:20200619T020000Z
						RRULE:FREQ=DAILY;UNTIL=20200622T180000
						SEQUENCE:0
						SUMMARY:Meeting
						LOCATION:Test
						TRANSP:OPAQUE
						UID:21B97459-D97B-4B23-AF2A-E2759745C299
						END:VEVENT
						END:VCALENDAR";
*/					

 		$contents = "BEGIN:VCALENDAR
						BEGIN:VEVENT
						CREATED:" . $current_date_time . "
						DTEND;TZID=America/Toronto:" . $end_date_time . "
						DTSTAMP:" . $current_date_time . "
						DTSTART;TZID=America/Toronto:" . $start_date_time . "
						LAST-MODIFIED:" . $current_date_time . "
						RRULE:FREQ=DAILY;UNTIL=" . $start_date_time . "
						SEQUENCE:0
						SUMMARY: " . $event_name . "
						LOCATION:" . $e['location'] . "
						TRANSP:OPAQUE
						UID:21B97459-D97B-4B23-AF2A-E2759745C299
						END:VEVENT
						END:VCALENDAR";

		$notes = $event_name . " Event reserved :  Start time :" . $start_date_time . " - End time :" . $end_date_time;
		
 		$url = tempnam("/tmp", "event");
 		file_put_contents($url, $contents);

 		$sendgrid = new \SendGrid(env('SENDGRID_API'));
 		$mail = new \SendGrid\Email();
 		$mail
 			->addTo([$data['email'], "memberships@thedepartureclub.com",])
 			->setFromName(" ")
 			->addHeader("TDC", "1")
 			->setFrom('noreplay@thedepartureclub.com')
 			->setSubject(' ')
 			->setHtml(' ')
 			->addAttachment($url, 'event.ics')
 			->addSubstitution( ':DATE_SEND', [date("F j, Y, g:i a")])
 			->addSubstitution( ':NAME', [$receiver_name])
 			->addSubstitution( ':EVENT_NAME', [$event_name])
 			->addSubstitution( ':EVENT_DATE', [$event_duration])
 			->addSubstitution( ':EVENT_SEATS', [$data['seats_count']])
 			->addSubstitution( ':EVENT_LOCATION', [$e['location']])
 			->addSubstitution( ':NOTES', [$notes])
 			->setTemplateId("430cfeb5-6fdd-4b09-9fd8-42ccdb7f0218");

 		$response = $sendgrid->send($mail);
		
		return Response::success($start_date_time);

	}

	public function get( $id )
	{

		//$events = new Events();

		$event_details =  Events::find($id);
		if(isset($event_details->event_image_id)) {
			
			$event_image = EventsImages::where('image_id', '=', $event_details->event_image_id)->firstOrFail();
			$event_details['event_image'] = $event_image->path . $event_image->name;
			
		}
		
		return Response::success($event_details);

	}
	
	public function edit( Request $request )
	{
		$data = $request->only([
			'id', 'name','location','description','seats','event_at','event_ends_at', 'event_image', 'date', 'period_from', 'period_to'
		]);

		$event_date_start = date('Y-m-d', strtotime($data['date']));
		$event_date_end = date('Y-m-d', strtotime($data['date']));
		
		$event_details = Events::where('id', '=', $data['id'])->update([
			'name' => $data['name'],
			'location' => $data['location'],
			'seats' => $data['seats'],
			'description' => $data['description'],
			'event_at' => date('Y-m-d H:i:s', strtotime($event_date_start . ' ' . $data['period_from'])),
			'event_ends_at' => date('Y-m-d H:i:s', strtotime($event_date_end . ' ' . $data['period_to']))
		]);

		return Response::success($event_details);
	}
	
	public function delete( $id )
	{

		//$events = new Events();

		$event_details =  Events::find($id)->delete();

		return Response::success($event_details);

	}


	
	public function create( Request $request )
	{
		$data = $request->only([
			'name','location','description','seats','event_at','event_ends_at', 'event_image', 'date', 'period_from', 'period_to'
		]);

		$event_date_start = date('Y-m-d', strtotime($data['date']));
		$event_date_end = date('Y-m-d', strtotime($data['date']));

		$event_details = Events::create([
			'name' => $data['name'],
			'location' => $data['location'],
			'seats' => $data['seats'],
			'description' => $data['description'],
			'event_at' => date('Y-m-d H:i:s', strtotime($event_date_start . ' ' . $data['period_from'])),
			'event_ends_at' => date('Y-m-d H:i:s', strtotime($event_date_end . ' ' . $data['period_to']))
		]);

		return Response::success($event_details);

	}

}
