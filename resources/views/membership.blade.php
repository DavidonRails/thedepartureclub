@extends('layouts.master')

@section('content')
<style>
	.membership-widget-right ul {
		text-align: left;
		padding-top: 25px;
	}
	.membership-widget-right ul li {
		position: relative;
		padding: 5px 5px 5px 30px;
	}
	.membership-widget-right ul li:before {
		content: '';
		position: absolute;
		top: 15px;
		left: 10px;
		width: 6px;
		height: 6px;
		border-radius: 100%;
		background: #000;
	}
	.membership-widget {
		width: 48%;
		max-width: 958px;
		float: left;
		margin: auto;
		margin: 10px;
	}
	.membership-widget-left, .membership-widget-right {
		display: table-cell;
		vertical-align: middle;
	}
	.membership-widget-left {
		background: #d1d2d4;
		text-align: right;
	}
	.membership-widget-left img{
		max-width: 570px;
		width: 100%;
	}
	.membership-widget-right {
		width: 360px;
		background: #fff;
		border: 1px solid #e4e4e4;
		padding: 55px 60px 75px;
	}
	.membership-widget-right button {
		margin-top: 40px;
	}
	@media (max-width: 992px) {
		.membership-widget-left {
			display: none;
		}

		.membership-widget-right {
			display: block;
			margin: 0px auto;
		}
	}


		.spinner {
			position: absolute;
			left: 50%;
			top: 50%;
			height:60px;
			width:60px;
			margin:0px auto;
			-webkit-animation: rotation .6s infinite linear;
			-moz-animation: rotation .6s infinite linear;
			-o-animation: rotation .6s infinite linear;
			animation: rotation .6s infinite linear;
			border-left:6px solid rgba(0,174,239,.15);
			border-right:6px solid rgba(0,174,239,.15);
			border-bottom:6px solid rgba(0,174,239,.15);
			border-top:6px solid rgba(0,174,239,.8);
			border-radius:100%;
			z-index: 99999;
		}

		@-webkit-keyframes rotation {
			from {-webkit-transform: rotate(0deg);}
			to {-webkit-transform: rotate(359deg);}
		}
		@-moz-keyframes rotation {
			from {-moz-transform: rotate(0deg);}
			to {-moz-transform: rotate(359deg);}
		}
		@-o-keyframes rotation {
			from {-o-transform: rotate(0deg);}
			to {-o-transform: rotate(359deg);}
		}
		@keyframes rotation {
			from {transform: rotate(0deg);}
			to {transform: rotate(359deg);}
		}

</style>
<main class="page-view" ng-controller="MembershipController" ng-init="autoPopup()">
	<section class="single-section">
		<div class="container">
			@foreach($packages as $package)
				<div class="membership-widget">
					<div class="membership-widget-right text-center">
							<h2 class="text-center">{{$package['name']}}</h2>
							<h4 class="text-center">${{ number_format($package['price'], 2, '.', ',') }}/{{ $package['billing_interval'] == 365 ? 'year' : 'month' }}</h4>
							<div class="">
								<ul>
									{!! $package['description'] !!}
									<!-- <li>{{ (float)$package['discount'] * 100 }}% discount flights for the whole plane</li> -->
								</ul>
							</div>
							
							<button class="btn btn-primary text-center" ng-click="showStripeForm( {{ $package }} )">
								Choose
							</button>
					</div>

				</div>
			@endforeach

			{{--<div class="packages">--}}
				{{----}}
				{{----}}
				{{----}}
				{{--<span class="flight-image"></span>--}}
				{{--<div class="package membership-package">--}}
					{{--<h2>Hobo Tier</h2>--}}
					{{--<div class="package-list">--}}
						{{--<ul>--}}
							{{--<li>$5,000 per year Membership Fee</li>--}}
							{{--<li>Summer Special is $2,500 </li>--}}
							{{--<li>Flights as low as $500 per hour for the whole plane</li>--}}
							{{--<li>Access to Departure Club tickets to Suns, Cardinals, D-Backs and other packages</li>--}}
							{{--<li>Departure Dinner Club</li>--}}
						{{--</ul>--}}
					{{--</div>--}}
					{{--<button class="btn btn-primary" ng-click="applyMembership()">Apply for Membership</button>--}}
				{{--</div>--}}


				{{--<div class="package membership-package">--}}
					{{--<h2>Founder Tier</h2>--}}
					{{--<div class="package-list">--}}
						{{--<ul>--}}
							{{--<li>$5,000 per year Membership Fee</li>--}}
							{{--<li>Summer Special is $2,500 </li>--}}
							{{--<li>Flights as low as $500 per hour for the whole plane</li>--}}
							{{--<li>Access to Departure Club tickets to Suns, Cardinals, D-Backs and other packages</li>--}}
							{{--<li>Departure Dinner Club</li>--}}
						{{--</ul>--}}
					{{--</div>--}}
					{{--<button class="btn btn-primary" ng-click="applyMembership()">Apply for Membership</button>--}}
				{{--</div>--}}


				{{--@foreach ($packages as $package)--}}

				{{--<div class="package @if($package['featured']) featured @endif">--}}
					{{--<h2>{{$package['name']}}</h2>--}}
					{{--<div class="package-free">--}}
						{{--@if($package['type'] == 1)--}}
							{{--<div>Free</div>--}}
						{{--@elseif($package['type'] == 2)--}}
							{{--<div>Pro</div>--}}
						{{--@else--}}
							{{--<sup>$</sup>{{$package['price']}}<sub>/mo</sub>--}}
						{{--@endif--}}
					{{--</div>--}}
					{{--<div class="package-list">--}}
						{{--<ul>--}}
							{{--<li>--}}
								{{--{!! $package['description'] !!}--}}
							{{--</li>--}}
						{{--</ul>--}}
					{{--</div>--}}
					{{--@if($package['package_id'] == $user_package)--}}
						{{--<button class="btn" disabled="">Current Plan</button>--}}
					{{--@else--}}
						{{--@if($package['type'] == 2 || $package['type'] == 0)--}}
							{{--<button class="btn btn-primary" ng-click="contactUs()">Contact us</button>--}}
						{{--@else--}}
							{{--@if(env('PAYPAL_SANDBOX'))--}}
								{{--@include('paypal.subscribe-web-sandbox', ['package' => $package])--}}
							{{--@else--}}
								{{--@include('paypal.subscribe-web', ['package' => $package])--}}
							{{--@endif--}}
							{{--<button class="btn btn-primary" ng-click="subscribe({{$package['package_id']}})">Select Package</button>--}}
						{{--@endif--}}
					{{--@endif--}}
				{{--</div>--}}

				{{--@endforeach--}}

			</div>
		</div><!-- /.container -->
	</section>
</main>
@include("membership.ng-templates")
@include("membership.stripe-form")
@stop
