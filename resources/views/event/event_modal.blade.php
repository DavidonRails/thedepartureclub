
<script type="text/ng-template" id="reverse-event-modal.html">
	<div class="modal modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Reservation Event</h3>
				</div>
				<div class="modal-body" flight-search>
					<form>
						<div class="row">
							<input type="hidden" ng-model="form.event_id" />
							<div class="hidden">
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" ng-model="form.first_name" placeholder="First Name">
										<span ng-if="errors.first_name" class="help-block has-error error-msg" ng-bind="errors.first_name"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" ng-model="form.last_name" placeholder="Last Name">
										<span ng-if="errors.last_name" class="help-block has-error error-msg" ng-bind="errors.last_name"></span>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<input class="form-control" type="text" ng-model="form.email" placeholder="Business Email Address">
										<span ng-if="errors.email" class="help-block has-error error-msg" ng-bind="errors.email"></span>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<input class="form-control" type="text" ng-model="form.phone" placeholder="Mobile Phone">
										<span ng-if="errors.phone" class="help-block has-error error-msg" ng-bind="errors.phone"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Number of Seats</label>
									<input class="form-control" type="number" ng-init="form.seats_count = 1" ng-model="form.seats_count" placeholder="1" value="1" min="1" max="99" maxlength="2">
									<span ng-if="errors.seats" class="help-block has-error error-msg" ng-bind="errors.sea"></span>
								</div>
							</div>
							<div class="col-md-12">
								<span ng-if="errors.other" class="help-block has-error error-msg" ng-bind="errors.other"></span>
								<div class="form-group" style="margin-top: 50px;">
									<button class="btn btn-primary btn-block" ng-click="reserveEvent(form)">
										Reservation Event
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
