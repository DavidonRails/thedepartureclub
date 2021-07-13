<!doctype html>
<html lang="en">
<head>
    <base href="{{ url() }}">
    <meta charset="UTF-8">
    <title>Departure Club</title>

        @if(Config::get('app.debug'))
            <link rel="stylesheet" href="{{ url('css/admin.css') }}">
            <link rel="stylesheet" href="{{ url('css/admin_vendor.css') }}">
        @else
            <link rel="stylesheet" href="{{ url('build/css/admin.css') }}">
            <link rel="stylesheet" href="{{ url('build/css/admin_vendor.css') }}">
    @endif

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
</head>
<body ng-app="hoboAdmin" class="theme-template-dark theme-pink alert-open alert-with-mat-grow-top-right">

@if($old == true)
    <div class="alert alert-warning text-center"><b>You are using browser version that is not supported. Please upgrade your browser.</b></div>
    <style>
        .navbar-fixed-top,
        aside.sidebar {
            top: 50px;
        }
    </style>
@endif

<main>

    @foreach (Alert::getMessages() as $type => $messages)
        @foreach ($messages as $message)
            <div class="alert alert-{{ $type }}">{{ $message }}</div>
        @endforeach
    @endforeach


    <aside class="sidebar fixed">
        <div class="brand-logo">
            Departure Club
        </div>
        <ul class="menu-links">

            @if(in_array(Auth::getUser()->role, ['admin', 'operator']))
                <li data-route="/">
                    <a href="{{url('admin')}}/#/">
                        <i class="md md-insert-chart"></i>&nbsp;<span>Dashboard</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin', 'operator']))
                <li data-route="/flights">
                    <a href="{{url('admin')}}/#/flights" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Flights</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin', 'operator']))
                <li data-route="/bookings">
                    <a href="{{url('admin')}}/#/bookings" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Bookings</span>
                    </a>
                </li>
            @endif
            @if(in_array(Auth::getUser()->role, ['admin', 'operator']))
                <li data-route="/bookings">
                    <a href="{{url('admin')}}/#/bookings-charter" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Charter bookings</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/aircrafts">
                    <a href="{{url('admin')}}/#/aircrafts" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Aircrafts</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/users">
                    <a href="{{url('admin')}}/#/users" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Users</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/notifications">
                    <a href="{{url('admin')}}/#/notifications" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Notifications</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/pack">
                    <a href="{{url('admin')}}/#/pack" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Hobo Squad</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/events">
                    <a href="{{url('admin')}}/#/events" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Events</span>
                    </a>
                    <ul class="in">
                        <li><a href="{{url('admin')}}/#/events/registrations">Event Registrations</a></li>
                    </ul>
                </li>
            @endif


            
            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/packages">
                    <a href="{{url('admin')}}/#/packages" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Packages</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li data-route="/settings">
                    <a href="{{url('admin')}}/#/settings" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Settings</span>
                    </a>
                    <ul class="in">
                        <li><a href="{{url('admin')}}/#/settings/promo">Promo</a></li>
                    </ul>
                 </li>
            @endif


            @if(in_array(Auth::getUser()->role, ['admin']))
                <li>
                    <a href="{{url('admin')}}/logs" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Logs</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::getUser()->role, ['admin']))
                <li>
                    <a href="{{url('admin')}}/#/password" >
                        <i class="md md-insert-chart"></i>&nbsp;<span>Password</span>
                    </a>
                </li>
            @endif

        </ul>
    </aside>
    <div class="main-container">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">


                <ul class="nav navbar-nav navbar-right navbar-right-no-collapse">
                    <li class="dropdown pull-right">
                        <a href="{{ url('auth/logout') }}">Logout</a>
                    </li>
                    <li class="dropdown pull-right">
                        <a href="#">{{Auth::getUser()->first_name}} {{Auth::getUser()->last_name}} <b>({{Auth::getUser()->role}})</b></a>
                    </li>
                </ul>

            </div>
        </nav>
        <div class="main-content">
            <section>
                @yield('content')
            </section>
        </div>
    </div>
    <div class="alert-container-top-right"></div>

</main>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

@if(Config::get('app.debug'))
    <script src="{{ url('js/dependencies.js') }}"></script>
@else
    <script src="{{ url('build/js/dependencies.js') }}"></script>
@endif

@if(Config::get('app.debug'))
    <script src="{{ url('js/app/templates.admin.js') }}"></script>
    <script src="{{ url('js/app/admin.js') }}"></script>
@else
    <script src="{{ url('build/js/admin.js') .'?' . strtotime('NOW') }}"></script>
@endif


</body>
</html>
