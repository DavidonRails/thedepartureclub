
<script type="text/ng-template" id="pro-member.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Hobo Pro</h3>
				</div>
				<div class="modal-body" flight-search>
					<form>
						<div class="form-group">
							<input class="form-control" type="text" ng-model="form.email" placeholder="Email Address">
							<span ng-if="errors.email" class="help-block has-error error-msg" ng-bind="errors.email"></span>
						</div>
						<div class="form-group">
							<textarea class="form-control" cols="30" rows="10" ng-model="form.message" placeholder="Message"></textarea>
							<span ng-if="errors.message" class="help-inline" ng-bind="errors.message"></span>
						</div>
						<div class="form-group" style="margin-top: 50px;">
							<button class="btn btn-primary btn-block" ng-click="send()">
								Send
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
<script type="text/ng-template" id="membership-modal.html">
	<div class="modal modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Create Account</h3>
				</div>
				<div class="modal-body" flight-search>
					<form>
						<div class="row">
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
									<input class="form-control" type="text" ng-model="form.phone" maxlength="10" placeholder="Mobile Phone">
									<span ng-if="errors.phone" class="help-block has-error error-msg" ng-bind="errors.phone"></span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input class="form-control" type="password" ng-model="form.password" placeholder="Password">
									<span ng-if="errors.password" class="help-block has-error error-msg" ng-bind="errors.password"></span>
								</div>
							</div>
							<div class="col-md-12">
								<span ng-if="errors.other" class="help-block has-error error-msg" ng-bind="errors.other"></span>
								<div class="form-group" style="margin-top: 50px;">
									<button class="btn btn-primary btn-block" ng-click="sendMembership(form)">
										Create Account
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
