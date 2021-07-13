@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="FlightsController as flights">
	<div class="page-cover" style="background-image: url(images/page-cover.png);">
		<div class="cover-title">
			<h1>Select Aircraft</h1>
			<p>Need a lift? Search for empty one-way private flights and score on amazing deal!</p>
		</div>
	</div>
	<section id="dealsFilter" class="deals-filter" flight-search>
		@if (Auth::check() && Auth::getUser()->membership != '')
		<form action="{{url('book-flight')}}" ng-submit="search($event)" method="post">
			<input type="hidden" name="origin_location_input">
			<input type="hidden" name="destination_location_input">
			<input type="hidden" name="price_from">
			<input type="hidden" name="price_to">
			<div class="container">
			<div class="row">

				<div class="col-md-12">
					<p class="filter-title">Filter Your Deals</p>
				</div>

				<div class="col-sm-4">
					<div class="form-group type">
						<input
							class="form-control"
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
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group type">
						<input
							class="form-control"
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
					</div>
				</div>
				
				<div class="col-md-3 col-sm-4">
					<table class="price-inline-form">
						<tr>
							<td><label>Price</label></td>
							<td>
								<div class="form-group">
									<input
										ng-model="range_price"
										class="form-control"
				                        data-animation="am-fade"
				                        data-placement="bottom-right"
				                        data-template-url="price-dropdown.html"
				                        bs-dropdown
				                        trigger="click"
				                        data-auto-close="1"
				                        placeholder="$0 - $2000"
				                        data-container=".price-inline-form"
				                        aria-expanded="false" readonly>
									<i class="form-control-ico ti-angle-down"></i>

			                        <script type="text/ng-template" id="price-dropdown.html">
			                            <div class="dropdown-menu price-dropdown" role="menu" ng-init="refreshSlider()">
											<rzslider
												rz-slider-model="slider_price.minValue"
										  		rz-slider-high="slider_price.maxValue"
										  		rz-slider-options="slider_price.options">
									        </rzslider>
			                            </div>
			                        </script>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-1 col-sm-12 membership_message">
					<div class="form-group">
						<button class="btn-filter-search" ng-click="search($event)"><i class="ti-search"></i></button>
					</div>
				</div>

			</div>
			<!-- <div class="row">
				<div class="col-md-12">
					<ul class="deals-filter">
						<li>
							<div class="form-group">
								<input id="" name="" type="text" placeholder="Origin" class="form-control">
							</div>
						</li>
						<li>
							<div class="form-group">
								<input id="" name="" type="text" placeholder="Origin" class="form-control">
							</div>
						</li>
						<li>
							<div class="form-inline">
								<label>Price</label>
								<div class="form-group">
									<input id="" name="" type="text" placeholder="Price" class="form-control">
								</div>
							</div>
						</li>
						<li>
							<div class="form-group">
								<button class="btn-filter-search"><i class="ti-search"></i></button>
							</div>
						</li>
					</ul>
				</div>
			</div> -->

		</div>
		</form>
		
		@else
		<div class="col-md-12">
			<div class="alert alert-info">
				<a href="/membership"><strong>Info!</strong> You need to get a membership for booking a flight. Please get a membership by subscription now.</a>
			</div>
		</div>
		@endif
	</section>

	<section class="search-flight-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="row">
						@if (count($flights['data']) == 0)
							<script>
								var no_results = true;
							</script>




							@if(count($search) == 2)
								<li class="col-md-12 text-center">There is no flight <b>{{$search['origin_location_input']['formatted_address']}}</b> - <b> {{$search['destination_location_input']['formatted_address']}}</b></li>

								<li  class="col-md-12 text-center" style="margin-bottom: 15px">
									Add Alert and we'll notify you whenever this route becomes available
								</li>

								<li  class="col-md-12 text-center">
									<button class="btn btn-primary" ng-click="createAlert({{json_encode($search)}})">Add Alert</button>
								</li>
							@else

								<li class="col-md-12 text-center">There is no flights</li>


							@endif
						@endif
						
						@foreach ($flights['data'] as $flight)
							<li class="col-md-4 col-sm-6">
								<a href="javascript:void(0)" class="product-card" ng-click="bookFlight({{$flight['flight_id'] }})">
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
														<div class="data" style="width:110px;">
															<span>$ {{ $flight['price'] - ($flight['price'] * (float)$discount) }} 
																(<font style="font-size:12px; text-decoration: line-through; color:gray;">$ {{ $flight['price'] }}</font>)
															</span>
															
															<small>FOR FULL JET</small>
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
							<li class="col-md-4 col-sm-6" ng-repeat="flight in flights.list.data" flight-details></li>
					</ul>

					<script type="text/javascript">
						var fp = '<?php echo json_encode($flights['pagination']) ?>';
					</script>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 text-center" ng-hide="flights.noMoreLoad">
					<button class="btn btn-primary" ng-click="loadMore()">Load more</button>
				</div>
			</div>

		</div><!-- /.container -->
	</section>
	<book-flight></book-flight>
</main>

@stop
