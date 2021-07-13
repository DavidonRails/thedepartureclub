@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="FlightsController as flights">
	<book-flight></book-flight>
	<section class="details-section">
		<div class="container">
			<div class="details">
				<div class="col-sm-6">
					<div class="flight-img">
						<div class="product-badge">
							<div class="days">{{$until_takeoff}} DAYS</div>
							<div class="until">UNTIL TAKEOFF</div>
						</div>
						<img src="{{url($aircraft_image)}}" alt="">
					</div>
				</div>
				<div class="col-sm-6">
					<article>
						<h3 class="flight-title">
							<span>Aircraft</span>
							<div>{{$aircraft_name}}</div>
						</h3>
						<hr>
						<div class="flight-price">
							<small>Price</small>
							<h1>${{$price}}</h1>
						</div>
						<div class="flight-info">
							<table>
								<tr>
									<td>
										<small>Departing from</small>
										<div>{{$origin_location}}</div>
									</td>
									<td>
										<small>Arriving to</small>
										<div>{{$destination_location}}</div>
									</td>
								</tr>
								<tr>
									<td>
										<small>Date</small>
										<div>{{$flight_start_human}}</div>
									</td>
									<td>
										<small>Number of Seats</small>
										<div>{{$seats}}</div>
									</td>
								</tr>
							</table>
						</div>
						<hr>
					</article>
					<div class="book-now">
						<button class="btn btn-primary xs-btn-full" ng-click="bookFlight({{$flight_id}})">Book Now</button>
					</div>
				</div>
			</div>
		</div><!-- /.container -->
	</section>
	<section>
		<div class="container">
			<div class="details">
				<div class="col-md-12">
					<h3 class="route-title">
						<span>Route Map</span>
						<div>{{$origin_location}} - {{$destination_location}}</div>
					</h3>
				</div>
			</div>
		</div>
		<div id="route-map" class="route-map"></div>
	</section>
	<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}"></script>
	<script>
		//Gmaps

		function initialize() {
			{{--var hoboJet = new google.maps.LatLng({{$origin_lat}},{{$destination_lon}});--}}
			var bounds = new google.maps.LatLngBounds();

			bounds.extend( new google.maps.LatLng({{$origin_lat}},{{$origin_lon}}) );
			bounds.extend( new google.maps.LatLng({{$destination_lat}},{{$destination_lon}}) );

			var mapOptions = {
				disableDefaultUI: true,
				scrollwheel: false,
				zoom: 4,
				center: bounds.getCenter(),
				styles: [{
					"featureType": "landscape", "elementType": "labels", "stylers": [{"visibility": "off"}]
				}, {
					"featureType": "transit",
					"elementType": "labels",
					"stylers": [{"visibility": "off"}]
				}, {
					"featureType": "poi",
					"elementType": "labels",
					"stylers": [{"visibility": "off"}]
				}, {
					"featureType": "water",
					"elementType": "labels",
					"stylers": [{"visibility": "off"}]
				}, {
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [{"visibility": "off"}]
				}, {"stylers": [{"hue": "#00aaff"}, {"saturation": -100}, {"gamma": 2.15}, {"lightness": 12}]}, {
					"featureType": "road",
					"elementType": "labels.text.fill",
					"stylers": [{"visibility": "on"}, {"lightness": 24}]
				}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 57}]}]
			};

			var map = new google.maps.Map(document.getElementById('route-map'), mapOptions);

			var flightPlanCoordinates = [
				{lat: {{$destination_lat}}, lng: {{$destination_lon}}},
				{lat: {{$origin_lat}}, lng: {{$origin_lon}}}
			];
			var flightPath = new google.maps.Polyline({
				path: flightPlanCoordinates,
				geodesic: true,
				strokeColor: '#009FD2',
				strokeOpacity: 1.0,
				strokeWeight: 3
			});

			new google.maps.Marker({
				position: new google.maps.LatLng({{$origin_lat}},{{$origin_lon}}),
				map: map,
				title: 'Hello World!'
			});


			new google.maps.Marker({
				position: new google.maps.LatLng({{$destination_lat}},{{$destination_lon}}),
				map: map,
				title: 'Hello World!'
			});

			map.fitBounds(bounds);
			flightPath.setMap(map);

			//   var marker = new google.maps.Marker({
			//       position: hoboJet,
			//       icon: 'images/map-pin.svg',
			//       map: map,
			//       title: 'hoboJet'
			//   });
		}

		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</main>

@stop
