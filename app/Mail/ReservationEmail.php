<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'sshere@exqsd.com';
        $subject = 'Event Reservation';
        $name = 'Stephan Shere';

        return $this->view('email.event_confirmation')
                    ->from("noreplay@thedepartureclub.com", "The Departure Club")
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with([ 
                        'event_name' => "Testing", 
                        'event_location' => 'Phoenix, AZ',
                        'event_seats' => 16, 
                        'event_date' => 'Test', 
                        'notes' => 'Notes....', 
                        'name' => 'John', ]);
    }
}


