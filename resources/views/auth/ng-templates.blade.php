
<!-- Login Modal -->
<script type="text/ng-template" id="login-modal.html">
	<div class="modal auth-modal" tabindex="-1" role="dialog" aria-hidden="true" ng-controller="LoginController as login">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Login</h3>
				</div>
				<div class="modal-body">
					<form novalidate name="form.login" ng-submit="login()">
						{{--<a href="javascript:void(0);" ng-click="facebook()" class="btn btn-fb btn-block">--}}
							{{--<span>Login with Facebook</span>--}}
						{{--</a>--}}
						{{--<div class="or-separate">--}}
							{{--or--}}
						{{--</div>--}}
						<div class="form-group">
							<input id="email" name="email" type="text" placeholder="Email" class="form-control" ng-model="email">
							<span class="form-control-ico ti-email"></span>
							<span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.email"></span>
						</div>
						<div class="form-group">
							<input id="password" name="password" type="password" placeholder="Password" class="form-control" ng-model="password">
							<span class="form-control-ico ti-lock"></span>
							<span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.password"></span>
						</div>

						<div class="clearfix">
							<div class="pull-left">
		                        <div class="form-group">
		                            <div class="checkbox">
		                                <input type="checkbox" id="check" name="remember" ng-model="remember">
		                                <label for="check">Remember me</label>
		                            </div>
		                        </div>
							</div>
							{{--<div class="pull-right">--}}
								{{--<div class="form-group text-right">--}}
									{{--<a class="normal" href="javascript:void(0)" ng-click="app.password()">--}}
			                    		{{--Forgot Password?--}}
			                    	{{--</a>--}}
								{{--</div>--}}
							{{--</div>--}}
						</div>

						<div class="form-group">
							<button class="btn btn-primary btn-block">
								Login
							</button>
						</div>
						{{--<p>Don’t have an account?--}}
							{{--<a class="normal" href="javascript:void(0)" ng-click="app.register()">--}}
	                    		{{--Register--}}
	                    	{{--</a>--}}
						{{--</p>--}}
					</form>
				</div>
			</div>
		</div>
	</div>
</script>



<!-- Register Modal -->
<script type="text/ng-template" id="register-modal.html">
	<div class="modal auth-modal" tabindex="-1" role="dialog" aria-hidden="true" ng-controller="RegisterController">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Register</h3>
				</div>
				<div class="modal-body">
					<form novalidate name="form.register" ng-submit="register()">
						<a href="javascript:void(0)" class="btn btn-fb btn-block" ng-click="facebook()">
							<span>Register with Facebook</span>
						</a>
						<div class="or-separate">
							or
						</div>
						<div class="form-group">
							<input id="email" name="email" type="text" class="form-control" placeholder="Email" ng-model="email">
							<span class="form-control-ico ti-email"></span>
							<span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>

						</div>
						<div class="form-group">
							<input id="first_name" name="first_name" type="text" placeholder="First name" class="form-control" ng-model="first_name">
							<span class="form-control-ico ti-user"></span>
							<span ng-message-exp="errors.first_name" class="help-inline" ng-bind="errors.first_name"></span>

						</div>
						<div class="form-group">
							<input id="last_name" name="last_name" type="text" placeholder="Last name" class="form-control" ng-model="last_name">
							<span class="form-control-ico ti-user"></span>
							<span ng-message-exp="errors.last_name" class="help-inline" ng-bind="errors.last_name"></span>

						</div>
						<div class="form-group">
							<input id="password" name="password" type="password" class="form-control" placeholder="Password"  ng-model="password">
							<span class="form-control-ico ti-lock"></span>
							<span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.password"></span>
						</div>


						<div class="clearfix">
							<div class="pull-right">
								<div class="form-group text-right">
									<a class="normal" href="#">Terms of Use</a>
								</div>
							</div>
						</div>

						<div class="form-group">
							<button class="btn btn-primary btn-block">
								Register
							</button>
						</div>
						<p>Already have an account?
							<a class="normal" href="javascript:void(0)" ng-click="app.login()">
	                    		Login
	                    	</a>
                    	</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- Password Modal -->
<script type="text/ng-template" id="password-modal.html">
	<div class="modal auth-modal" tabindex="-1" role="dialog" aria-hidden="true" ng-controller="ResetController">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Forgot Password</h3>
				</div>
				<div class="modal-body">
					<form novalidate name="form.reset" ng-submit="reset()">
						<label for="email">Type your email address</label>
						<div class="form-group">
							<input id="email" name="email" type="text" placeholder="Email" class="form-control" ng-model="email">
							<span class="form-control-ico ti-email"></span>
							<span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>
						</div>

						<div class="form-group">
							<button class="btn btn-primary btn-block">
								Reset Password
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- Subscribe Modal -->
<script type="text/ng-template" id="subscribe-modal.html">
	<div class="modal subscribe-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Receive Exclusive Flight Deals!</h3>
				</div>
				<div class="modal-body">
					<form novalidate name="" ng-submit="">
						<div class="form-group">
							<input id="name" name="name" type="text" placeholder="Your Name" class="form-control" ng-model="form.name">
							<span class="form-control-ico ti-user"></span>
						</div>
						<div class="form-group">
							<input id="email" name="email" type="text" placeholder="Your Email" class="form-control" ng-model="form.email">
							<span class="form-control-ico ti-email"></span>
							<span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>
						</div>
						<div class="form-group">
							<div class="ui-select">
								<select ng-model="form.state">
									<option value="" disabled selected hidden>Select State</option>
									<option value="Alabama">Alabama</option>
									<option value="Arizona">Arizona</option>
									<option value="Arkansas">Arkansas</option>
									<option value="California">California</option>
									<option value="Colorado">Colorado</option>
									<option value="Connecticut">Connecticut</option>
									<option value="Delaware">Delaware</option>
									<option value="Florida">Florida</option>
									<option value="Georgia">Georgia</option>
									<option value="Hawaii">Hawaii</option>
									<option value="Idaho">Idaho</option>
									<option value="Illinois">Illinois</option>
									<option value="Indiana">Indiana</option>
									<option value="Iowa">Iowa</option>
									<option value="Kansas">Kansas</option>
									<option value="Kentucky">Kentucky</option>
									<option value="Louisiana">Louisiana</option>
									<option value="Maine">Maine</option>
									<option value="Maryland">Maryland</option>
									<option value="Massachusetts">Massachusetts</option>
									<option value="Michigan">Michigan</option>
									<option value="Minnesota">Minnesota</option>
									<option value="Mississippi">Mississippi</option>
									<option value="Missouri">Missouri</option>
									<option value="Montana">Montana</option>
									<option value="Nebraska">Nebraska</option>
									<option value="Nevada">Nevada</option>
									<option value="New Hampshire">New Hampshire</option>
									<option value="New Jersey">New Jersey</option>
									<option value="New Mexico">New Mexico</option>
									<option value="New York">New York</option>
									<option value="North Carolina">North Carolina</option>
									<option value="North Dakota">North Dakota</option>
									<option value="Ohio">Ohio</option>
									<option value="Oklahoma">Oklahoma</option>
									<option value="Oregon">Oregon</option>
									<option value="Pennsylvania">Pennsylvania</option>
									<option value="Rhode Island">Rhode Island</option>
									<option value="South Carolina">South Carolina</option>
									<option value="South Dakota">South Dakota</option>
									<option value="Tennessee">Tennessee</option>
									<option value="Texas">Texas</option>
									<option value="Utah">Utah</option>
									<option value="Vermont">Vermont</option>
									<option value="Virginia">Virginia</option>
									<option value="Washington">Washington</option>
									<option value="West Virginia">West Virginia</option>
									<option value="Wisconsin">Wisconsin</option>
									<option value="Wyoming">Wyoming</option>
									<option value="District of Columbia">District of Columbia</option>
									<option value="Puerto Rico">Puerto Rico</option>
									<option value="Guam">Guam</option>
									<option value="American Samoa">American Samoa</option>
									<option value="U.S. Virgin Islands">U.S. Virgin Islands</option>
									<option value="Northern Mariana Islands">Northern Mariana Islands</option>
								</select>
								<span class="form-control-ico ti-angle-down"></span>
							</div>
						</div>

						<div class="form-group" style="margin-top: 30px;">
							<button class="btn btn-primary btn-block" ng-click="send(form)">
								Accept & Subscribe
							</button>
						</div>

						<div class="by-clicking">
							<small><i class="ti-lock"></i>By clicking 'Accept and Subscribe', you will receive all the latest HJ news and deals. Including, promos from our trusted partners to help maximize your trips</small>.
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
