<script type="text/ng-template" id="stripe-form-modal">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true" ng-controller="stripeController">
		<div class="spinner" ng-show="stripe_progress"></div>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">

					<h3 class="modal-title" style="text-align:center;">
						<div class="logo">
							<img src="/images/black_logo.png" alt="" width="210" height="auto" />
						</div>
					</h3>

					<button type="button" class="close" aria-label="Close" ng-click="$hide()" style="margin-top: -120px;">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row" style="background: #ddd; padding: 15px 20px;">
						<h4>Order Summary</h4>
						<table width="100%">
							<tbody>
								<tr>
									<td>Plan</td>
									<td style="text-align:right;">{{form.package_name}}</td>
								</tr>
								
								<tr>
									<td>Duration</td>
									<td style="text-align:right;">{{form.duration}}</td>
								</tr>

								<tr ng-if="form.show_trial">
									<td>Free Trial Period</td>
									<td style="text-align:right;">7 days</td>
								</tr>

								<tr style="border-bottom: 1px solid #888;">
									<td>Founder Membership</td>
									<td style="text-align:right;">${{form.price}}/{{form.duration == 'Annual' ? 'year' : 'month'}}</td>
								</tr>

								<tr>
									<td><h5>Due Today</h5></td>
									<td style="text-align:right;"><h5>${{form.price}}</h5></td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="row" style="margin: 25px 0 10px 0;"><h4>Payment Information</h4> </div>
					
					<form id="subscription-form">
						<input type="hidden" ng-model="form.package_id" />
						
						<div class="form-group">
							<div id="card-element" class="MyCardElement">
							</div>
						</div>
						
						<div class="form-group">
							<div id="card-errors" role="alert">
							</div>
						</div>
						
						<div class="form-group">
							<input type="checkbox" id="isChecked" name="isChecked" ng-model="form.isChecked" >
							<label for="isChecked">I accept your Terms of Service and Privacy Policy, and understand I am authorizing a payment to be drafted from my credit card automatically.</label><br>
						</div>
						<div class="form-group" style="margin-top: 50px;">
							<button class="btn btn-primary btn-block" ng-click="stripeProcess(form)"   ng-disabled="!form.isChecked">
								Complete Payment
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
