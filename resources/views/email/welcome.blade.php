
@extends('email.master_email')

@section('content')

    <p>
        Please confirm your email by clicking on the link below:
    </p>
    <p>
        <a href="{{ env('APP_URL') . 'auth/verify/' . $confirmation_code }}" target="_blank" class="button">Confirm</a>
    </p>
    <p>
        HoboJet team
    </p>

@stop