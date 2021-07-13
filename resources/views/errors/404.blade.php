<!doctype html>
<html lang="en">
<head>
    <base href="{{ url() }}">
    <title>Departure Club</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,300,700,900">
    @if(Config::get('app.debug'))
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('build/css/app.css') }}">
    @endif
<!-- FONTS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <script>
        var fbid = '{{getenv('FACEBOOK_APP_ID')}}'
    </script>
</head>
<body ng-app="app" ng-controller="AppController">
<section class="error-404">
    <article>
        <img src="{{url('images/logo.svg')}}" alt="">
        <h1>404</h1>
        <p>Oops... this page doesn't exist!</p>
        <a class="" href="{{url('/')}}">Back To Home</a>
    </article>
</section>
</body>
</html>
