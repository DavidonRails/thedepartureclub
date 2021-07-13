@extends('email.master_email')

@section('content')

    <p>
        Congratulations on selecting HoboJet for luxury private flights.
    </p>
    <p>
        Your login details are as follows:
    </p>
    <p>
        Email: <b>{{$email}}</b> <br>
        Password: <b>{{$password}}</b>
    </p>
    <p>
        HoboJet team
    </p>

@stop