@extends('layouts.master')

@section('body-content')
<section class="hero">
    {{--<div class="hero-image" style="background-image: url(images/hero.png);"></div>--}}
    <div class="hero-image" style="background-image: url(images/hero-jetguy.png);"></div>
    <div class="hero-content">
        <div class="hero-heading">
            <h1>Need a lift?</h1>
            <p>Search for empty one-way private flights and score an amazing deal!</p>
        </div>

        <div class="hero-search" flight-search>
			<form action="{{url('book-flight')}}" ng-submit="search($event)" method="post">
				<input type="hidden" name="origin_location_input">
				<input type="hidden" name="destination_location_input">
				<ul class="inline-hero-form">
					<li class="search-origin">
						<input
							ng-model="origin_location"
							bs-options="data as data.formatted_address for data in citySearch($viewValue)"
							placeholder="Origin"
							data-animation="am-fade"
							data-min-length="0"
							data-limit="3"
							bs-typeahead
							data-trim-value="false" ng-trim="false"
							type="text">
							<i class="form-control-ico ico-orig"></i>
					</li>
					<li class="search-destination">
						<input
							ng-model="destination_location"
							bs-options="data as data.formatted_address for data in citySearch($viewValue)"
							placeholder="Destination"
							data-animation="am-fade"
							data-min-length="0"
							data-limit="3"
							bs-typeahead
							data-trim-value="false" ng-trim="false"
							type="text">
							<i class="form-control-ico ico-dest"></i>
					</li>
					<li class="search-button">
						<button class="btn btn-block btn-primary" ng-click="search($event)">Find a Jet</button>
					</li>
				</ul>
			</form>
        </div>
    </div>

    <div class="scroll-down">
        <a href="#flights-section">
            <img src="images/scroll-down.svg" alt="">
        </a>
        <ul class="vertical-dots">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </div>
    </div>
</section>
@stop
@section('content')

@if (Auth::check())
<section class="flights-section" id="flights-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="section-title">
					<h1 class="h1">Featured Deals</h1>
					<p>Check out some of our featured deals and save up to 96% on Private Jet Flights!</p>
				</div>
			</div>
		</div>
		<div class="row" ng-controller="FlightsController as flights">
			<book-flight></book-flight>
			<ul class="row">
				@foreach ($flights as $flight)
				<li class="col-sm-4">
					<a href="javascript:void(0)" class="product-card" ng-click="bookFlight({{$flight['flight_id']}})">
						<article>
							<div class="product-badge">
								<div class="days">{{$flight['until_takeoff']}} DAYS</div>
								<div class="until">UNTIL TAKEOFF</div>
							</div>
							<div class="product-img">
								<img src="{{$flight['aircraft_image']}}" alt="">
								<ul class="from-to">
									<li class="from">
										<span>
											<img src="{{url('images/ico/origin-ico.svg')}}" alt="">
											{{$flight['route_origin']}}
										</span>
									</li>
									<li class="to">
										<span>
											<img src="{{url('images/ico/destination-ico.svg')}}" alt="">
											{{$flight['route_destination']}}
										</span>
									</li>
								</ul>
							</div>
							<div class="product-info">
								<table>
									<tr>
										<td class="data-cell">
											<div class="data">
												<span>{{$flight['flight_start_human']}}</span>
												<small>DEPARTING</small>
											</div>
										</td>
										<td class="data-cell seats">
											<div class="data">
												<span>{{$flight['seats']}}</span>
												<small>SEATS</small>
											</div>
										</td>
										<td class="data-cell">
											<div class="data">
												<span>${{ $flight['price'] - ($flight['price'] * (float)$discount) }}</span>
												<small>FOR FULL JET </small>
											</div>
										</td>
									</tr>
								</table>
								<button class="btn-angle">
									<span class="ti-angle-right"></span>
								</button>
							</div>
						</article>
					</a>
				</li>
				@endforeach
			</ul>

			<!--div class="col-md-4 hidden-sm hidden-xs">
				<div class="widget become-member">
					<h1>RECEIVE  EXCLUSIVE  FLIGHT DEALS</h1>
					<p>Additional flight discounts for a modest membership fee.</p>
					<div>
						<a href="{{url('membership')}}" class="btn btn-primary btn-block">Become a Member</a>
					</div>
				</div>
				{{--<div class="widget discount">--}}
					{{--<h1>10% DISCOUNT</h1>--}}
					{{--<p>On all our destinations for your first flight</p>--}}
					{{--<div class="widget-link">--}}
						{{--<a href="{{url('book-flight')}}">View All Deals</a>--}}
					{{--</div>--}}
				{{--</div>--}}
			</div-->
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<a class="btn btn-primary" href="{{url('book-flight')}}">View All</a>
			</div>
		</div>

	</div><!-- /.container -->
</section>
@endif

@if (!Auth::check())
<section class="container" ng-controller="MembershipController" style="margin-right: auto; margin-left: auto; text-align: center; padding: 40px 0">
	<section class="single-section" style="padding: 0px;" >
		@foreach($packages as $package)
			<div class="membership-widget" style="padding-top: 0;">
				<div class="membership-widget-right text-center">
						<h2 class="text-center">{{$package['name']}}</h2>
						<h4 class="text-center">${{ number_format($package['price'], 2, '.', ',') }}/{{ $package['billing_interval'] == 365 ? 'year' : 'month' }}</h4>
							<ul class="">
								{!! $package['description'] !!}
							</ul>
							
						<button class="btn btn-primary text-center" ng-click="createUserAccount({{ $package }})">
							Start Now
						</button>
				</div>
			</div>
		@endforeach
	</section>
</section>

@endif

<section class="testimonials-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<slick settings="testimonialsConfig" class="testimonials-slider">
				   <div>
				   		<article>
				   			{{--<div class="testimonials-avatar" style="background-image: url('images/user-hobo.png');">--}}
				   			<div class="testimonials-avatar" style="background-image: url('images/symbol.png');">
				   			</div>
					   		<h6 class="text-uppercase">Troy</h6>
					   		<p>“Our family takes advantage of our membership by getting out of the desert in the summer and escaping to much cooler places”</p>
					   	</article>
				   </div>
				   <div>
				   		<article>
				   			<div class="testimonials-avatar" style="background-image: url('images/symbol.png');">
				   			</div>
					   		<h6 class="text-uppercase">Jeff</h6>
					   		<p>“The flight is more fun than the destination”</p>
					   	</article>
				   </div>
				   <div>
				   		<article>
				   			<div class="testimonials-avatar" style="background-image: url('images/symbol.png');">
				   			</div>
					   		<h6 class="text-uppercase">Tony</h6>
					   		<p>“If you have never taken a Private Jet than that should be on top of your bucket list”</p>
					   	</article>
				   </div>
				</slick>
			</div>
		</div>
	</div>
</section>

@include("membership.ng-templates")
@include("membership.stripe-form")
@stop
