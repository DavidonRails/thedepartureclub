<!doctype html>
<html lang="en">
<head>
    <base href="{{ url() }}">
    <title>Departure Club</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="Departure Club is the most cost efficient way to travel private! We use our operators for great flight deals and giveaways for you and your friends!">
    <meta name="keywords" content="Departure Club, travel, private, flight, deals, flight deals, charter flight, charter, plane, private plane, private">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{url('images/favicon.png')}}">

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
        @if(Auth::check())
            user = '<?php echo json_encode($user); ?>';
        @endif
		
		var STRIPE_SECRET_KEY = '{{getenv('STRIPE_SECRET_KEY')}}';
		var STRIPE_PUBLISH_KEY = '{{getenv('STRIPE_PUBLISH_KEY')}}';
		
    </script>
	<script src="https://js.stripe.com/v3/"></script>
</head>
<body class="{{(Request::is('/') ? 'homepage' : '')}}" ng-app="app" ng-controller="AppController as app">

    <div id="pagePreload">
        <div id="status">&nbsp;</div>
    </div>

<header>
    <div class="logo">
        <a href="{{url('')}}">
            {{--<img src="{{url('images/logo.svg')}}" alt="">--}}
            <img src="{{url('images/black_logo.png')}}" alt="">
        </a>
    </div>
    <button id="menu-btn" class="mobile-menu-btn"><i class="ti-menu"></i></button>
    <div class="main-menu">
        <ul class="mobile">
            @if (Auth::check())
                <li>
                    <a href="{{ url('profile') }}" class="user-logged">
                        <div>
                            <img src="{{$user['avatar']}}" alt="">
                            <p>{{ $user['first_name'] }} {{ $user['last_name'] }}</p>
                        </div>
                    </a>
                </li>
            @endif
        </ul>

        <ul>
            <li>
                <a class="{{{(Request::is('book-flight') ? 'active' : '')}}}" href="{{url('book-flight')}}">One-Way Specials</a>
            </li>
            {{--<li>--}}
                {{--<a class="{{{(Request::is('flight-status') ? 'active' : '')}}}" href="{{url('flight-status')}}">Flight Status</a>--}}
            {{--</li>--}}
            <li>
                <a class="{{{(Request::is('charter-flight','select-aircraft') ? 'active' : '')}}}" href="{{url('charter-flight')}}">Charter</a>
            </li>
            <li>
                <a class="{{{(Request::is('flights-alerts') ? 'active' : '')}}}" href="{{url('flights-alerts')}}">Set Alerts</a>
            </li>
			
			@if (Auth::check() && Auth::getUser()->membership != '')
				{{--<Hidden Membership Tab when user has already a plan by payment agreement>--}}
			@else
				<li>
					<a class="{{{(Request::is('membership') ? 'active' : '')}}}" href="{{url('membership')}}">Membership</a>
				</li>
			@endif
			
            @if(Auth::check())
                <li>
                    <a class="{{{(Request::is('events') ? 'active' : '')}}}" href="{{url('events')}}">Events</a>
                </li>
			@endif
			
            <!-- Mobile User Menu li items -->
            @if (Auth::check())
                @if (Auth::getUser()->role == 'admin' || Auth::getUser()->role == 'operator')
                    <li class="li-mobile">
                        <a href="{{ url('admin/') }}">
                            @if(Auth::getUser()->role == 'operator')
                                Dashboard
                            @endif
                            @if(Auth::getUser()->role == 'admin')
                                Admin
                            @endif
                        </a>
                    </li>
                @endif
				
                <!-- <li class="li-mobile"><a href="{{ url('profile') }}">Settings</a></li> -->
                <li class="li-mobile"><a href="{{ url('auth/logout') }}">Log Out</a></li>
            @endif
            @if (!Auth::check())
                <li class="li-mobile">
                    <a href="javascript:void(0)" ng-click="app.login()">
                        Login
                    </a>
                </li>
                {{--<li class="li-mobile">--}}
                    {{--<a href="javascript:void(0)" ng-click="app.register()">--}}
                        {{--Register--}}
                    {{--</a>--}}
                {{--</li>--}}
            @endif
            <!-- / Mobile User Menu li items -->
        </ul>
    </div>

    <div class="user-menu">
        <ul>
            @if (Auth::check())
                <li>
                    <a href=""
                        class="user-logged"
                        data-animation="am-fade-and-slide-right"
                        data-placement="bottom-right"
                        data-template-url="loged-menu.html"
                        data-container="header"
                        bs-dropdown
                        aria-expanded="false">
                        <div>
                            <span>{{ $user['first_name'] }} {{ $user['last_name'] }}</span>
                            <img src="{{$user['avatar']}}" alt="">
                        </div>
                        <script type="text/ng-template" id="loged-menu.html">
                            <ul class="logged-dropdown-menu dropdown-menu" role="menu">
                                @if (Auth::getUser()->role == 'admin' || Auth::getUser()->role == 'operator')
                                    <li>
                                        <a href="{{ url('admin/') }}">
                                            @if(Auth::getUser()->role == 'operator')
                                                Dashboard
                                            @endif
                                            @if(Auth::getUser()->role == 'admin')
                                                Admin
                                            @endif
                                        </a>
                                    </li>
                                @endif
                                <!--<li>
                                    <a href="{{ url('profile') }}"><i class="ti-pencil-alt"></i>Edit Profile</a>
                                </li>-->
                                <li><a href="{{ url('profile') }}"><i class="ti-settings"></i>Settings</a></li>
                                <li><a href="{{ url('auth/logout') }}"><i class="ti-power-off"></i>Log Out</a></li>
                            </ul>
                        </script>
                    </a>
                </li>
            @endif
            @if (!Auth::check())
                <li>
                    <a href="javascript:void(0)" ng-click="app.login()">
                        Login
                    </a>
                </li>
                {{--<li>--}}
                    {{--<a href="javascript:void(0)" ng-click="app.register()">--}}
                        {{--Register--}}
                    {{--</a>--}}
                {{--</li>--}}
            @endif
        </ul>
    </div>
    @if (Auth::check())
        <button class="search-btn" onclick="location.href='{{ url('book-flight') }}'">
            <i class="ti-search"></i>
        </button>
    @else
        <button class="search-btn search-btn-logout" onclick="location.href='{{ url('book-flight') }}'">
            <i class="ti-search"></i>
        </button>
    @endif
</header>
@yield('body-content')

<div class="page-container">

    @foreach (Alert::getMessages() as $type => $messages)
        @foreach ($messages as $message)
            <div class="alert alert-{{ $type }}">{{ $message }}</div>
        @endforeach
    @endforeach

    @yield('content')
</div>

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

@if(Request::path() !== '/' && Request::path() !== 'auth/login')
<section class="subscribe-section" subscribe>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h1">Receive Additional Flight Deals</h1>
                <p>Save up to 40% of your next trip. Secret Deals for our subscribers only.</p>
            </div>
            <div class="col-md-6">
                <div class="subscribe-form">
                    <table>
                        <tr>
                            <td>
                                <input type="email" class="form-control" ng-model="email" placeholder="Your Email">
                            </td>
                            <td>
                                <button class="btn btn-primary btn-block" ng-click="send()">Subscribe</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- footer -->
<footer>
    <div class="container-fluid">
        <div class="row footer-top">
            <div class="col-sm-2">
                <div class="footer-logo">
                    {{--<img src="images/footer-logo.svg" alt="">--}}
                    <img src="images/white_logo.png" alt="">
                </div>
            </div>
            <div class="col-sm-8">
                <ul class="footer-nav">
                    <li>
                        <a href="{{url('/')}}">Home</a>
                    </li>
                    <li>
                        <a href="{{url('book-flight')}}">One-Way Specials</a>
                    </li>
                    <li>
                        <a href="{{url('charter-flight')}}">Charter</a>
                    </li>
                    <!-- <li>
                        <a href="">Blog</a>
                    </li> -->
                    <li>
                        <a href="{{url('contact')}}">Contact</a>
                    </li>
                    <li>
                        <a href="{{url('faq')}}">FAQ</a>
                    </li>

                </ul>
            </div>
            <div class="col-sm-2">
                <table class="footer-social">
                    <tr>
                        {{--<td>--}}
                            {{--<a href="https://www.facebook.com/hobojet" target="_blank">--}}
                                {{--<i class="ti-facebook"></i>--}}
                            {{--</a>--}}
                        {{--</td>--}}
                        <td>
                            <a href="https://www.instagram.com/privatejettravel/" target="_blank">
                                <i class="ti-instagram"></i>
                            </a>
                        </td>
                        {{--<td>--}}
                            {{--<a href="https://twitter.com/hobojets" target="_blank">--}}
                                {{--<i class="ti-twitter"></i>--}}
                            {{--</a>--}}
                        {{--</td>--}}
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row footer-bottom">
            <div class="col-sm-6">
                <div class="copyright">
                    <p>Copyright@ {{ date("Y") }} Departure Club</p>
                    {{--<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet. </p>--}}
                </div>
            </div>
            <div class="col-sm-6">
                <ul class="legal list-inline">
                    <li><a href="{{url('terms-conditions')}}">Terms & Conditions</a></li>
                    <!-- <li><a href="">Privacy Policy</a></li> -->
                </ul>
            </div>
        </div>
    </div>
</footer>
@include("auth.ng-templates")
@include("flightdetails.ng-templates")
<div id="fb-root"></div>

</body>
</html>
