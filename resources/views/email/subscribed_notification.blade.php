
@extends('email.master_email')

@section('content')

    <p>
        A new user has subscribed and paid!
    </p>
    <p>
	<b>Name</b> <span>{{ $name }}<span><br/>
        <b>Email</b> <span>{{ $email }}<span><br/>
        <b>Plan</b> <span>{{ $plan }}<span><br/>
    <p>

@stop
