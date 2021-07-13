
<!-- Fight Details -->
<script type="text/ng-template" id="flight-details.html">
	<div class="modal flight-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<form>
						<div class="row">
							<div class="col-sm-6">
								<div class="flight-img">
									<div class="product-badge">
										<div class="days">@{{flight_details.until_takeoff}} DAYS</div>
										<div class="until">UNTIL TAKEOFF</div>
									</div>
									<img ng-src="@{{flight_details.aircraft_image}}" alt="">
								</div>
							</div>
							<div class="col-sm-6">
								<article>
									<h3 class="modal-title">
										<span>Aircraft</span>
										<div>@{{flight_details.aircraft_name}}</div>
									</h3>
									<hr>
									<div class="flight-price">
										<small>Price @{{!flight_details.show_discount}}</small>
										
										<h2>$ @{{ flight_details.show_discount? flight_details.discount_price : flight_details.price }}
										<font ng-if="flight_details.show_discount" style="font-size:0.8em; text-decoration: line-through; color:gray;">( $@{{ flight_details.price }} )</font>
										</h2>
										
										
									</div>
									<div class="flight-info">
										<table>
											<tr>
												<td>
													<small>Departing from</small>
													<div>@{{flight_details.origin_location}}</div>
												</td>
												<td>
													<small>Arriving to</small>
													<div>@{{flight_details.destination_location}}</div>
												</td>
											</tr>
											<tr>
												<td>
													<small>Date</small>
													<div>@{{flight_details.flight_start_human}}</div>
												</td>
												<td>
													<small>Number of Seats</small>
													<div>@{{flight_details.seats}}</div>
												</td>
											</tr>
										</table>
									</div>
									<hr>
									<div class="flight-availability">
										<p class="normal">When are you available to fly?</p>
										{{--<rzslider--}}
												{{--data-t="@{{flight_time.minValue}}"--}}
											{{--rz-slider-model="flight_time.minValue"--}}
											{{--rz-slider-high="flight_time.maxValue"--}}
											{{--rz-slider-options="flight_time.options">--}}
								        {{--</rzslider>--}}
										<table class="from-to-wrapper">
											<tr>
												<td>
													<span>from</span>
												</td>
												<td>
													<button class="btn-time-picker"
														ng-model="time_start"
														data-time-format="HH:mm"
														data-min-time="@{{time_period.min_time}}"
														data-max-time="@{{time_period.max_time}}"
														bs-timepicker
														data-length="3"
														trigger="focus"
														data-minute-step="1"
														data-auto-close="1"
														data-icon-up="ti-angle-up"
														data-icon-down="ti-angle-down">
														<i class="ti-time"></i><span>@{{time_start|moment_filter:'HH:mm'}}</span>
													</button>
												</td>
												<td>
													<span>to</span>
												</td>
												<td>
													<button class="btn-time-picker"
														ng-model="time_end"
														data-time-format="HH:mm"
														data-min-time="@{{time_start}}"
														data-max-time="@{{time_period.max_time}}"
														bs-timepicker
														data-length="3"
														trigger="focus"
														data-minute-step="1"
														data-auto-close="1"
														data-icon-up="ti-angle-up"
														data-icon-down="ti-angle-down">
														<i class="ti-time"></i><span>@{{time_end|moment_filter:'HH:mm'}}</span>
													</button>
												</td>
											</tr>
										</table>
										<div class="row">
											<div class="col-xs-4">

											</div>
											<div class="col-xs-4">
											</div>
										</div>
									</div>
								</article>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="modal-footer">
									<div class="modal-footer-btn">
										<button class="btn btn-gray" ng-click="$hide()">Cancel</button>
										<button ng-click="step('open-submit')" class="btn btn-primary">
											Continue Booking
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- Submit Modal -->
<script type="text/ng-template" id="submit-modal.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Submit Booking Request</h3>
				</div>
				<!-- Passenger list Content -->
				<div class="modal-body">
					<p>Submit your interest in booking this flight to @{{flight_details.destination_location}} on @{{flight_details.flight_start_human}}</p>
					<div class="alert alert-danger" ng-if="error">
						<strong>Error:</strong> @{{error}}
					</div>

					<div class="row">
						<div class="col-md-12">
							<input class="form-control" placeholder="Number of Passengers" ng-model="flight_details.number_of_passengers" type="number" required name="number_of_passengers"/>
							<br/>
							<div class="form-group">
								<button class="btn btn-primary btn-block" ng-click="step('submit-request')">
									Submit Request
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- Passenger Modal -->
<script type="text/ng-template" id="passenger-modal.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Passengers(@{{flight.passangers.length}}/@{{flight_details.seats}})</h3>
				</div>
				<!-- Passenger list Content -->
				<div class="modal-body" ng-if="passengersTab == 'list'">
					<div class="alert alert-danger" ng-if="error">
						<strong>Error:</strong> @{{error}}
					</div>
						<div class="row">
							<div class="col-md-12">
								<ul class="passenger-list">
									<li ng-repeat="passenger in flight.passangers">
										<a href="javascript:void(0)" ng-click="editPassenger(passenger, $index)">
											@{{passenger.first_name}} @{{passenger.last_name}}
											<div class="pass-info">
												<span ng-if="passenger.birth_date == null">Birth: ?</span>
												<span ng-if="passenger.birth_date != null">Birth: @{{passenger.birth_date | date: 'shortDate'}}</span>
												<span ng-if="passenger.weight == null">Weight: ?</span>
												<span ng-if="passenger.weight != null">Weight: @{{passenger.weight}}</span>
											</div>
											<span class="remove-passenger" ng-click="removePassenger($index)">
												<i class="ti-close"></i>
											</span>
										</a>
									</li>
								</ul>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group text-right">
									<a href class="add-passenger-link" ng-hide="flight.passangers.length == flight_details.seats" ng-click="passengersTabAction('form')">Add</a>
								</div>

								<div class="form-group">
									<button class="btn btn-gray btn-block" ng-click="step('time')">
										Back
									</button>
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-block" ng-click="step('checkout')">
										Continue
									</button>
								</div>
							</div>
						</div>
				</div>

				<!-- Add Passenger Content -->
				<div class="modal-body" ng-if="passengersTab == 'form'">
					<div class="alert alert-danger" ng-if="error">
						<strong>Error:</strong> @{{error}}
					</div>
					<form name="passengers" novalidate>
						<div class="row">
							<div class="col-md-12 add-passenger-inputs">
								<div class="form-group">
									<input type="text" name="first_name" placeholder="Firstname" class="form-control" ng-model="passengers_form.first_name" required>
								</div>
								<div class="form-group">
									<input type="text" name="last_name" placeholder="Lastname" class="form-control"  ng-model="passengers_form.last_name" required>
								</div>
								<div class="form-group">
									<input
										type="text"
										name="dob"
										ng-model="passengers_form.dob"
										placeholder="Birthdate MM/DD/YYYY"
										class="form-control">
									<i class="form-control-ico ti-calendar"></i>
								</div>
								<div class="form-group">
									<input type="number" name="weight" placeholder="Weight (lbs)" class="form-control" ng-model="passengers_form.weight" required="" min="1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<button
										class="btn btn-gray btn-block"
										ng-click="passengersTabAction('list')">
										Back
									</button>
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-block" ng-click="addPassenger(passengers)" ng-if="passengers_form.type == 'add'">
										Save
									</button>
									<button class="btn btn-primary btn-block" ng-click="updatePassenger(passengers)" ng-if="passengers_form.type == 'edit'">
										Edit
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- Flight itinerary -->
<script type="text/ng-template" id="flight-itinerary.html">
	<div class="modal flight-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
						<div class="row">
							<div class="col-sm-6">
								<div class="flight-img">
									<div class="product-badge">
										<div class="days">@{{flight_details.until_takeoff}} DAYS</div>
										<div class="until">UNTIL TAKEOFF</div>
									</div>
									<img ng-src="@{{flight_details.aircraft_image}}" alt="">
								</div>
							</div>
							<div class="col-sm-6">
								<article>
									<h3 class="modal-title">
										<div>Flight itinerary</div>
									</h3>
									<legend class="text-uppercase">Flight Details</legend>
									<!--<div class="flight-price">
										<small>Price</small>
										<h1>$950</h1>
									</div>-->
									<div class="flight-info">
										<table>
											{{--<tr>--}}
												{{--<td>--}}
													{{--<small>Name</small>--}}
													{{--<div>Pero Peric</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
											<tr>
												<td>
													<small>Aircraft</small>
													<div>@{{flight_details.aircraft_name}}</div>
												</td>
												{{--<td>--}}
													{{--<small>Flight</small>--}}
													{{--<div>Pending</div>--}}
												{{--</td>--}}
											</tr>
											<tr>
												<td>
													<small>Origin</small>
													<div>@{{flight_details.origin_location}}</div>
												</td>
												<td>
													<small>Destination</small>
													<div>@{{flight_details.destination_location}}</div>
												</td>
												{{--<td>--}}
													{{--<small>Arrival</small>--}}
													{{--<div>Pending</div>--}}
												{{--</td>--}}
											</tr>
											<tr>
												<td>
													<small>Date</small>
													<div>@{{ flight_details.flight_start_human }}</div>
												</td>
											</tr>
											<tr>
												<td>
													<small>Passenger(s)</small>
													<div>@{{passengers_lenght}}</div>
												</td>
												<td>
													<small>Number of Seats</small>
													<div>@{{ flight_details.seats }}</div>
												</td>
												{{--<td>--}}
													{{--<small>Airport facilties</small>--}}
													{{--<div>5</div>--}}
												{{--</td>--}}
											</tr>
										</table>
									</div>
									<legend class="text-uppercase">TOTAL PRICE (including Tax)</legend>
									<h2>$@{{ flight_details.price }}</h2>
									<div class="alert alert-danger" ng-if="error">
										<strong>Error:</strong> @{{error}}
									</div>
								</article>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="modal-footer">
									<div class="modal-footer-btn">
										<button class="btn btn-gray" ng-click="step('passengers')">Go back</button>
										@if(env('PAYPAL_SANDBOX'))
											@include('paypal.book-sandbox')
										@else
											@include('paypal.book')
										@endif
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</script>



<!-- Trip Details (Flight Status modal) -->
<script type="text/ng-template" id="trip-details.html">
	<div class="modal flight-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<form>
						<div class="row">
							<div class="col-sm-6">
								<div class="flight-img">
									<div class="product-badge">
										<div class="days">@{{booking.until_takeoff}} DAYS</div>
										<div class="until">UNTIL TAKEOFF</div>
									</div>
									<img ng-src="@{{booking.aircraft_image}}" alt="">
								</div>
							</div>
							<div class="col-sm-6">
								<article>
									<h3 class="modal-title">
										<div>Trip Details</div>
									</h3>
									<legend class="text-uppercase">Status <small class="pull-right badge">@{{booking.status}}</small></legend>
									<legend class="text-uppercase">Flight Details</legend>
									<div class="flight-info">
										<table>
											{{--<tr>--}}
												{{--<td>--}}
													{{--<small>Name</small>--}}
													{{--<div>Pero Peric</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
											<tr>
												<td>
													<small>Aircraft</small>
													<div>@{{booking.aircraft_name}}</div>
												</td>
												{{--<td>--}}
													{{--<small>Flight</small>--}}
													{{--<div>@{{booking.status}}</div>--}}
												{{--</td>--}}
											</tr>
											<tr>
												<td>
													<small>Origin</small>
													<div>@{{booking.flight_origin}}</div>
												</td>
												<td>
													<small>Airport facilties</small>
													<div>@{{booking.flight_origin_airport_info.name}}</div>
												</td>
											</tr>
											<tr>
												<td>
													<small>Destination</small>
													<div>@{{booking.flight_destination}}</div>
												</td>
												<td>
													<small>Airport facilties</small>
													<div>@{{booking.flight_destination_airport_info.name}}</div>
												</td>
											</tr>
											<tr>
												<td>
													<small>Date</small>
													<div>@{{booking.departure_date_human}}</div>
												</td>
												<td>
													<small>Passenger(s)</small>
													<div>@{{ booking.flight_passengers_count }}</div>
												</td>
											</tr>
											<tr>
												{{--<td>--}}
													{{--<small>Number of Seats</small>--}}
													{{--<div>6</div>--}}
												{{--</td>--}}
												{{--<td>--}}
													{{--<small>Airport facilties</small>--}}
													{{--<div>5</div>--}}
												{{--</td>--}}
											</tr>
										</table>
									</div>
									<legend class="text-uppercase">TOTAL PRICE (including Tax)</legend>
									<h2>$@{{booking.price}}</h2>
								</article>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="modal-footer">
									<div class="modal-footer-btn" ng-if="booking.status != 'Waiting for payment'">
										<a class="btn btn-primary" target="_blank" href="https://maps.google.com?daddr=@{{booking.flight_origin_airport_info.name}}">
											Get Directions
										</a>
									</div>
									<div class="modal-footer-btn" ng-if="booking.status == 'Waiting for payment'">
										<button class="btn btn-primary" ng-click="finishBooking()" >
											Finish booking
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
