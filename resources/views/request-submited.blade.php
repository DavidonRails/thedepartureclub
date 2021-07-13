@extends('layouts.master')

@section('body-content')
	{{--<section class="hero" style="background-image: url(images/hero.png);" ng-init="showRequestModal()">--}}
	<section class="hero" style="background-image: url(images/hero-jetguy.png);" ng-init="showRequestModal()">
	</section>
	<!-- Request Modal -->
	<script type="text/ng-template" id="request-modal.html">
		<div class="modal request-modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Passenger list Content -->
					<div class="modal-body">
						<article class="text-center">
							<img class="modal-logo" src="images/logo.svg" alt="">
							<p>Your request has been received. <br>
							You will be notified  by Email as soon as your Itinerary is ready.</p>
							<a href="{{url("/")}}" class="btn btn-primary">Home</a>
						</article>
					</div>
				</div>
			</div>
		</div>
	</script>
@stop