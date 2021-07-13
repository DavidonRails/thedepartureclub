
@extends('email.master_email')

@section('content')

    <p>
        We have received a request to change email
    </p>
    <p>
        Use the link below to approve
    </p>
    <p>
        <a href="{{ env('APP_URL') . 'verify/' . $confirmation_code }}" target="_blank" class="button">Approve link</a>
    </p>
    <p>
        HoboJet team
    </p>

@stop