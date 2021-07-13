

@extends('email.master_email')

@section('content')

    <p>
        We have received a request to reset your HoboJet password
    </p>
    <p>
        Use the link below to change password
    </p>
    <p>
        <a href="{{ url('password/reset/'.$token) }}" target="_blank" class="button">Reset link</a>
    </p>
    <p>
        HoboJet team
    </p>

@stop