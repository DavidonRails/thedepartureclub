@extends('layouts.master')

@section('content')
<style>
.event-listing--has-image .event-listing__header {
    height: 65vh;
    max-height: inherit;
    /* min-height: 500px; */
    overflow: hidden;
    position: absolute;
    width: 100%;
	display: block;
}

.listing-hero-image__blurry-background {
	background-repeat: no-repeat;
	background-size : 100%;
	height: 100%;
	width: 100%;
	/* The image used */
	background-image: url("photographer.jpg");

	/* Add the blur effect */
	filter: blur(50px);
	-webkit-filter: blur(50px);

	/* Center and scale the image nicely */
	background-position: center;
	background-size: cover;
}

.panel-date {
    font-weight: normal;
	color : #39364f;
	text-transform: uppercase;
	margin-bottom : 30px;
}

.panel-title {
	display: -webkit-box;
    max-height: 125;
    margin: 0 auto;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #1E0A3C;
    font-weight: 600;
    font-size: 20px;
    line-height: 21px;
    letter-spacing: .5px;
    word-break: break-word;
}

.button-panel {
    border-style: solid;
    border-color: #DBDAE3;
    border-width: 1px 0;
    background-color: #fff;
    padding-top: 10px;
    padding-bottom: 10px;
    box-sizing: border-box;
}

.event-detail-page .single-section {
	display: block;
	padding-top:80px;
	padding-bottom: 40px;
	/* Add the blur effect */
	filter: blur(0px);
	-webkit-filter: blur(0px);
}

.event-detail-page .container {
	background-color: white;
	display:block;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.15);
}

.event-detail-page .detail-panel {
    -webkit-font-smoothing: antialiased;
    color: #39364f;
    font-size: 1.2em;
    line-height: 2.5em;
}

</style>
<main class="page-view event-listing--has-image event-detail-page" ng-controller="EventsController">
	<div class="event-listing__header clrfix hide-small hide-medium">
		<div class="listing-hero-image__blurry-background fx--fade-in fx--delay-4" style="background-image: url('{{$event['event_image']}}')">
		</div>
	</div>
	<section class="single-section">
		<div class="container">
			<div class="row">

				<div class="col-md-8" style="padding:0px;">
						<div class="img-div" style="height: 370px; width: 100%; background: url('{{$event['event_image']}}')"></div>
				</div>
				
				<div class="col-md-4" style="padding:0px;">
					<div class="panel " style="background-color='#f6f3ec';  border-radius:0px; height: 370px; margin:0px;">
						<div class="panel-content">
							<div class="panel-body" style="padding: 30px; height: 325px;">
								<h1 class="panel-title" style="margin-bottom:15px">{{$event['name']}}</h1>
								<!--span>{{$event['event_image']}}</span-->

					<p class="text-body">
						 <b>{{$event['event_start_date']}}</b><br/>
                                                                        <font size="2"></font> 
                                                                        <b>{{ $event['event_start_time'] }}</b> 
                                                                        <font size="2">  to  </font> 
                                                                        <b>{{ $event['event_end_time'] }}</b>
					</p>
					<strong class="text-caption">
                                                Event Seats
                                        </strong>
                                        <p class="text-body">{{$event['seats']}}</p>

                                        <strong class="text-caption">
                                                Location
                                        </strong>
                                        <p class="text-body">{{$event['location']}}</p>

							</div>
						</div>
					</div>
				</div>

			</div>
 
			<div class="row button-panel">

				<div class="col-md-8" style="padding:0px;">
					
				</div>
				<div class="col-md-4" style="padding:0px;">
					<a class="btn btn-primary" style="width: 80%; margin-left:10%; margin-right: 10%;" ng-click='applyevents( {{ $event }} )'>
						Reserve Your Seat
					</a>
				</div>

            </div>

            <div class="row detail-panel">
                <div class="col-md-8" style="padding: 40px 10%;">

					<strong class="text-caption">
						About this Event 
					</strong>
					<p class="text-body">{!! $event['description'] !!}</p>
			

                </div>    
            </div>

		</div><!-- /.container -->

	</section>
</main>
@include("event.event_modal")
@stop
