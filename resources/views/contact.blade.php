@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="ContactController">
	<div class="page-cover" style="background-image: url(images/page-cover.png);">
		<div class="cover-title">
			<h1>We'd Love To Hear From You</h1>
			<p>If you have any questions, comments or concerns feel free to contact us below</p>
		</div>
	</div>
	<section class="single-section">
		<div class="container">
			<div class="row contact-row">
				<div class="col-md-7">
					<div class="contact-column">
						<h4>Send Us Message</h4>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<input id="name" name="name" type="text" placeholder="Name" ng-model="form.name" class="form-control">
									<span ng-message-exp="errors.name" class="help-inline" ng-bind="errors.name"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="form-group">
										<input id="phone" name="phone" type="text" placeholder="Phone" ng-model="form.phone" class="form-control">
										<span ng-message-exp="errors.phone" class="help-inline" ng-bind="errors.phone"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input id="email" name="email" type="text" placeholder="Email" ng-model="form.email" class="form-control">
									<span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" placeholder="Message" rows="8" ng-model="form.message" id="message"></textarea>
									<span ng-message-exp="errors.message" class="help-inline" ng-bind="errors.message"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<button class="btn btn-primary xs-btn-full" ng-click="send(form)">Submit</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="contact-column info">
						<h4>Get in Touch</h4>
						{{--<article>--}}
							{{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit culpa voluptas porro corporis vel eius quis accusantium esse, quos.</p>--}}
						{{--</article>--}}

						<div class="row">
							<div class="col-sm-12">
								<ul>
									<li class="normal">Email</li>
									<li>josh@hobojet.com</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.container -->
	</section>
	{{--<section>--}}
		{{--<div id="hobomap" class="hobomap">--}}

		{{--</div>--}}
	{{--</section>--}}
	{{--<script src="https://maps.googleapis.com/maps/api/js"></script>--}}
	{{--<script>--}}
		{{--//Gmaps--}}

		{{--function initialize() {--}}
		  {{--var hoboJet = new google.maps.LatLng(33.6257607,-111.9064349);--}}

		  {{--var mapOptions = {--}}
		    {{--disableDefaultUI: true,--}}
		    {{--scrollwheel: false,--}}
		    {{--zoom: 12,--}}
		    {{--center: hoboJet,--}}
		    {{--styles: [{--}}
		        {{--"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#00aaff"},{"saturation":-100},{"gamma":2.15},{"lightness":12}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":57}]}]--}}
		  {{--};--}}

		  {{--var map = new google.maps.Map(document.getElementById('hobomap'), mapOptions);--}}

		  {{--var marker = new google.maps.Marker({--}}
		      {{--position: hoboJet,--}}
		      {{--icon: 'images/map-pin.svg',--}}
		      {{--map: map,--}}
		      {{--title: 'hoboJet'--}}
		  {{--});--}}
		{{--}--}}

		{{--google.maps.event.addDomListener(window, 'load', initialize);--}}
	{{--</script>--}}
</main>

@stop
