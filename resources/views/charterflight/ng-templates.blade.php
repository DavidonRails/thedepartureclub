
<script type="text/ng-template" id="charter-flight-modal.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Charter</h3>
				</div>
				<div class="modal-body" flight-search>
						<div class="form-group type">
							<input
								class="form-control"
								ng-model="form.origin_location"
								bs-options="data as data.formatted_address for data in citySearch($viewValue)"
								placeholder="Origin"
								data-animation="am-fade"
								data-min-length="0"
								data-limit="6"
								bs-typeahead
								data-trim-value="false" ng-trim="false"
								type="text">
							<i class="form-control-ico ico-orig"></i>
							<span ng-message-exp="errors.origin" class="help-inline" ng-bind="errors.origin"></span>
						</div>
						<div class="form-group type">
							<input
								class="form-control"
								ng-model="form.destination_location"
								bs-options="data as data.formatted_address for data in citySearch($viewValue)"
								placeholder="Destination"
								data-animation="am-fade"
								data-min-length="0"
								data-limit="6"
								bs-typeahead
								data-trim-value="false" ng-trim="false"
								type="text">
							<i class="form-control-ico ico-dest"></i>
							<span ng-message-exp="errors.destination" class="help-inline" ng-bind="errors.destination"></span>
						</div>
						<hr/>
						<table class="form-inline-table">
							<tr>
								<td>
									<label>Passengers</label>
								</td>
								<td>
									<input type="number" ng-init="form.passengers_count = 1" ng-model="form.passengers_count" class="form-control" placeholder="1" value="1" min="1" maxlength="10">
								</td>
							</tr>
						</table>

						<div class="row">
							<div class="col-sm-12">
								<label>Date and Time</label>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<input
										type="text"
										ng-model="form.flight_date_start_date"
										bs-datepicker
										data-min-date="@{{min_date}}"
										data-autoclose="1"
										data-icon-left="ti-arrow-left"
										data-icon-right="ti-arrow-right"
										placeholder="Date"
										class="form-control">
									<i class="form-control-ico ti-calendar"></i>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<input
										class="form-control"
										placeholder="Time"
										ng-model="form.flight_date_start_time"
										data-time-format="HH:mm"
										bs-timepicker
										data-length="1"
										trigger="focus"
										data-minute-step="1"
										data-auto-close="1"
										data-icon-up="ti-angle-up"
										data-icon-down="ti-angle-down"
										data-arrow-behavior="picker">
									<i class="form-control-ico ti-time"></i>
								</div>
							</div>
							<div class="col-xs-12" style="margin-top: -15px;">
								<span ng-message-exp="errors.date_start" class="help-inline" ng-bind="errors.date_start"></span>
							</div>
						</div>

						<div class="row" ng-if="return == true">
							<div class="col-sm-12">
								<label>Return Date and Time</label>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<input
										type="text"
										ng-model="form.flight_date_end_date"
										bs-datepicker
										data-min-date="@{{form.flight_date_start_date}}"
										data-autoclose="1"
										data-icon-left="ti-arrow-left"
										data-icon-right="ti-arrow-right"
										placeholder="Date"
										class="form-control">
									<i class="form-control-ico ti-calendar"></i>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<input
										class="form-control"
										placeholder="Time"
										ng-model="form.flight_date_end_time"
										data-time-format="HH:mm"
										bs-timepicker
										data-length="1"
										trigger="focus"
										data-minute-step="1"
										data-auto-close="1"
										data-icon-up="ti-angle-up"
										data-icon-down="ti-angle-down"
										data-arrow-behavior="picker">
									<i class="form-control-ico ti-time"></i>
								</div>
							</div>

							<div class="col-xs-12" style="margin-top: -15px;">
								<span ng-message-exp="errors.date_end" class="help-inline" ng-bind="errors.date_end"></span>
							</div>
						</div>

						<div class="form-group" style="margin-top: 50px;">
							<button class="btn btn-default btn-block no-trans" ng-click="returnFlight(false)" ng-if="return == true">
								<span>Remove</span> Return Flight
							</button>
							<button class="btn btn-default btn-block no-trans" ng-click="returnFlight(true)" ng-if="return == false">
								<span>Add</span> Return Flight
							</button>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" ng-click="getAirplanes(form)">
								Continue
							</button>
						</div>
				</div>
			</div>
		</div>
	</div>
</script>
