

@extends('email.master_email')

@section('content')


    <p>
        Congratulations {$name}, your reservation has been placed.
    </p>

    <p><b>{$event_name}</b></p>
    <p>{$event_seats} seats</p>
    <p>{$event_location}</p>
    <p>{$event_date}</p>   
    <p><b>Notes</b><br/>{$notes}</p>  

 </div>
    <p>
        HoboJet team

    </p>

@stop