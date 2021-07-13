@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="AlertsController">
	<section class="single-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="section-title">
						<h1 class="h1">Set Alerts</h1>
					</div>
				</div>
			</div>

			<div class="alert-row" id="alerts_list">
				@foreach($alerts as $alert)
			    <div class="alert-col" id="alert_{{$alert['alert_id']}}">
					<button class="remove-alert-btn" ng-click="delete({{$alert['alert_id']}})"><i class="ti-close"></i></button>
			    	<article class="alert-box">
						<p class="alert-orig">{{$alert['origin']}}</p>
						<p class="alert-dest">{{$alert['destination']}}</p>
						{{--<p class="alert-message">New flight deal available for route</p>--}}
			    	</article>
			    </div>
				@endforeach

			</div><!-- /.alert-row -->

			<!-- no alerts -->
			<div class="row text-center">
				@if(count($alerts) == 0)
				<div class="col-md-12">
					<p class="no-alerts">
						You Have No Alerts! <br> Click the button below to create one.
					</p>
				</div>
				@endif
				<div class="col-md-12">
					<button class="btn btn-primary" ng-click="create()">
						Add Alert
					</button>
				</div>
			</div>
			<!-- /no alerts -->
			
		</div><!-- /.container -->

	</section>
</main>
@include("flightsalerts.ng-templates")
@stop
