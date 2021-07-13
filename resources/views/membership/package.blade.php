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
        var fbid = '{{getenv('FACEBOOK_APP_ID')}}';
        var user = null;
    </script>
</head>
<body ng-app="app" ng-controller="AppController">
    <div id="pagePreload">
        <div id="status">&nbsp;</div>
    </div>

    <section class="single-package" ng-controller="SubscribeController">
        <article>
            <h3>{{$package_name}}</h3>
            <p class="price">${{$package_price}} per month</p>
            {{--<ul class="package-info">--}}
				{{--<li>Access to 5x number of flights</li>--}}
				{{--<li>Premium routes (Vegas, NYC, ect)</li>--}}
				{{--<li>More round trip filghts</li>--}}
				{{--<li>Occasional Free flights</li>--}}
			{{--</ul>--}}
            <ul>
                <li>
                    {{$package_description}}
                </li>
            </ul>
            <div class="form-group" ng-if="">
                <button class="btn btn-primary btn-block">Select Package</button>
            </div>

            <div class="paypal-btn-wrap">
                @if($package_price == 0)
                    <button class="btn btn-primary btn-block" ng-click="subscribe('{{$user_token}}', {{$package_id}})">Select Package</button>
                @else
                    @if(env('PAYPAL_SANDBOX'))
                        @include('paypal.subscribe-sandbox')
                    @else
                        @include('paypal.subscribe')
                    @endif
                @endif
            </div>

        </article>
    </section>

    @if(Config::get('app.debug'))
        <script src="{{ asset('js/dependencies.js') }}"></script>
    @else
        <script src="{{ asset('build/js/dependencies.js') }}"></script>
    @endif

    @if(Config::get('app.debug'))
        <script src="{{ asset('js/app/app.js') }}"></script>
    @else
        <script src="{{ asset('build/js/app.js') }}"></script>
    @endif
</body>
</html>
