@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="BookingsController">
	<section class="single-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="section-title">
						<h1 class="h1">Flight Status</h1>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<ul class="row">
					@if (count($bookings['data']) == 0)
						<li class="col-md-12 text-center">No results</li>
					@endif
					@foreach ($bookings['data'] as $booking)
						<li class="col-md-4 col-sm-6">
							<a class="product-card" href="javascript:void(0)" ng-click="bookingDetails({{$booking['booking_id']}})">
								<article>
									<div class="product-badge">
										<div class="days">{{$booking['until_takeoff']}} DAYS</div>
										<div class="until">UNTIL TAKEOFF</div>
									</div>
									<div class="product-img">
										<img src="{{$booking['aircraft_image']}}" alt="">
										<ul class="from-to">
											<li class="from">
												<span>
													<img src="{{url('images/ico/origin-ico.svg')}}" alt="">
													{{$booking['flight_origin']}}
												</span>
											</li>
											<li class="to">
												<span>
													<img src="{{url('images/ico/destination-ico.svg')}}" alt="">
													{{$booking['flight_destination']}}
												</span>
											</li>
										</ul>
									</div>
									<div class="product-info">
										<table>
											<tr>
												<td class="data-cell">
													<div class="data">
														<span>{{$booking['departure_date_human']}}</span>
														<small>DEPARTING</small>
													</div>
												</td>
												<td class="data-cell seats">
													<div class="data">
														<span>{{$booking['aircraft_seats']}}</span>
														<small>SEATS</small>
													</div>
												</td>
												<td class="data-cell">
													<div class="data">
														@if($booking['status'] == 'Waiting for approval' || $booking['status'] == 'Waiting for payment')
															<span class="waiting-for">{{$booking['status']}}</span>
														@else
															<span>{{$booking['status']}}</span>
														@endif
														<small>STATUS</small>
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
				</div>
			</div>

			<!-- no flights -->
			<div class="row text-center" ng-if="">
				<div class="col-md-12">
					<p class="no-flights">
						You don't have booked flights.
					</p>
				</div>
			</div>
			<!-- /no flights -->

		</div><!-- /.container -->
	</section>
</main>

@stop
