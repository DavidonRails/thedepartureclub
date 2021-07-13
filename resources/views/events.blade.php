@extends('layouts.master')

@section('content')
<style>
.event-title {
	color: #1E0A3C;
}

.event-title h3 {
	margin-top: 10px;
}

.event-time {
    padding-bottom: 0px;
}

.btn-read-more {
    background-color: #032a63;
    border-radius: 2px;
    color: white;
    padding: 5px 10px;
	border: 1px solid #032a63;
}

.btn-read-more:hover {
    background-color: #ffffff;
    color: #032a63;
}
</style>
<main class="page-view" ng-controller="EventsController">
	<section class="single-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="section-title">
						<h1 class="h1">Events</h1>
					</div>
				</div>
			</div>
 
			<div class="row">
                <div class="col-md-12">
                    <div class="container-fluid">
                        <div class="row">
                        @foreach($events as $event)
                            <div class="col-md-12 mt-3">
                           
                                    <div class="panel ">
                                        <div class="card-horizontal">
                                            <div class="img-square-wrapper">
                                                <div class="img-div" style="background: url('{{ $event['event_image'] == '' ? 'https://www.ochch.org/wp-content/themes/mast/images/empty-photo.jpg' : $event['event_image'] }}');"></div>
                                            </div>
                                            <div class="panel-content">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title event-time pull-right">
														<b>{{$event['event_start_date']}}</b> 
														<font size="2">  from  </font> 
														<b>{{ $event['event_start_time'] }}</b> 
														<font size="2">  to  </font> 
														<b>{{ $event['event_end_time'] }}</b>
													</h3>
                                                </div>
                                                <div class="panel-body">
													<div class="event-title">
														<h3>{{$event['name']}}</h3> 
													</div>
                                                    <div class="panel-text">
														<!--div id="desc-box" class="desc-box" ng-bind="{!! $event['description'] !!}">
														</div-->
                                                        <p>{!! $event['description'] == '' ? 'No description provided.' : substr($event['description'], 0, 300) !!}</p>
														
                                                    </div>
                                                </div>
                                                <div class="panel-footer">
                                                    <a href="/events/{{$event['id']}}" class="btn btn-sm btn-read-more">
														Read More
													</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>    
            </div>

		</div><!-- /.container -->

	</section>
</main>
@include("flightsalerts.ng-templates")
@stop
