export var membership = angular.module('hobo.membership', [])
	.controller('MembershipController', function(api, $scope, $window, $rootScope, $modal, Alert){
		var membership = this;
		
		$scope.form = {
			email: null,
			message: null,
			// user_id: $rootScope.user.user_id
		};

		$scope.errors = {};

		var modal = null;
		
		$scope.contactUs = () => {

			modal = $modal({
				'scope': $scope,
				'templateUrl': 'pro-member.html'
			});

		}
		
		$scope.autoPopup = () => {
			
			if($rootScope.user.package) {
				$scope.showStripeForm($rootScope.user.package);
			}
		}
		
		$scope.createUserAccount = (p) => {
			
			// New User Creation Form
			modal = $modal({
				'scope': $scope,
				'templateUrl': 'membership-modal.html'
			});
			
			modal.$scope.form.package = p;
			/*
			modal.$scope.form.first_name = "wei";
			modal.$scope.form.last_name = "wang";
			modal.$scope.form.email = "wwwwwang@exqsd.com";
			modal.$scope.form.phone = "6825039100";
			modal.$scope.form.password = "123456789";
			*/
			modal.$promise.then(modal.show);
		};

		$scope.sendMembership = (form) => {
			console.log("Package ID : ", form.package['package_id']);
			console.log("User Information :", $rootScope.user);

			$scope.errors = {};
			var stop_submit = false;

			if($rootScope.user) { // When user logged in
				
			} else { // When user log out or has no account and plan.

				
				if(!form.first_name || form.first_name.length < 2){
					$scope.errors['first_name'] = 'Please enter first name of 2 or more characters';
					stop_submit = true;
				}

				if(!form.last_name || form.last_name.length < 2){
					$scope.errors['last_name'] = 'Please enter last name of 2 or more characters';
					stop_submit = true;
				}

				if(!form.email){
					$scope.errors['email'] = 'Please enter email';
					stop_submit = true;
				}

				if(!form.phone){
					$scope.errors['phone'] = 'Please enter phone number';
					stop_submit = true;
				}
				
				if(!form.password || form.password.length < 8){
					$scope.errors['password'] = 'Please enter password of 8 or more characters';
					stop_submit = true;
				}

				if(stop_submit){
					return false;
				}
				
				var data = {
					'package_id': form.package['package_id'],
					'first_name': form.first_name,
					'last_name': form.last_name,
					'email' : form.email,
					'phone' : form.phone,
					'password' : form.password,

				};

				
				api.call('contact/apply-membership', data)
					.success((res) => {
						
						console.log("Apply Membership : ", data);
						
						if(res.status == 1){
							
							modal.$promise.then(modal.hide);
							
							if(res.responseData.user['active'] == 2) {
								window.location.href = '/membership';
							} else {
								var customerId = '';
								
								// Get Stripe customer id or creation new Stripe User
								api.call('stripe/getCustomerId', {
									user_id: res.responseData['user_id']
								})
								.success((result) => {
									console.log("Customer Id Success! : ", result);
									customerId = result;
									
									// Stripe Form
									$scope.stripeFormModal.$scope.form = {
										user_id : res.responseData['user_id'],
										package_id : form.package.package_id,
										price_id : form.package.stripe_price_id,
										customer_id : customerId,
									};
								})
								.catch((err) => {
									alert('Error occurred');
									console.log(err);
								});

								$scope.stripeFormModal = $modal({
									'scope': $scope,
									'controller' : 'stripeController',
									'templateUrl': 'stripe-form-modal',
									'title': 'Subscription',
									'show': false
								});

								$scope.stripeFormModal.$promise.then($scope.stripeFormModal.show);
							}
							
						}else{
							$scope.errors = res.messages;
						}
					});
			
			}
			

		}

		$scope.send = () => {

			api.call('contact/membership', $scope.form)
				.success((res) => {
					if(res.status == 1){
					
						Alert.info(res.responseData.message);
						modal.$promise.then(modal.hide)
					}else{
						$scope.errors = res.messages;
					}
				});
			
		};

		$scope.subscribe = (package_id) => {

			api.call('paypal/subscribe/new/web', {
				package_id: package_id
			})
				.success((res)=>{
					if(res.status == 1){

						if(typeof res.responseData.pending_id == 'undefined'){
							window.location.reload();
							return;
						}
						
						var pending_id = res.responseData.pending_id;

						var form = angular.element(document.getElementById('paypalform'));

						form.append(
							'<input type="hidden" name="custom" value="s:'+pending_id+'">'
						);

						form.submit();
					}
				});

		};
		
		$scope.stripeFormModal = null;
		
		$scope.showStripeForm = (p) => {
			
			console.log("Package information :", p);
			console.log("User information :", $rootScope.user);
			
			var customerId = $rootScope.user.customer_id;
			
			// Get Stripe customer id or creation new Stripe User
			api.call('stripe/getCustomerId', {
				user_id: $rootScope.user.user_id
			})
			.success((res) => {
				console.log("Customer Id Success! : ", res);
				customerId = res;
				
				// Stripe Form
				$scope.stripeFormModal.$scope.form = {
					user_id : $rootScope.user.user_id,
					package_name : p['name'],
					package_id : p['package_id'],
					price_id : p['stripe_price_id'],
					price : p['price'],
					billing_interval: p['billing_interval'],
					customer_id : customerId,
					isChecked : false,
					show_trial : (p['name'] == 'Founders Membership') ? false : true,
					duration : (p['billing_interval'] == 30) ? 'Monthly' : 'Annual',
				};
				
				console.log("Stripe Form Params : ", $scope.stripeFormModal.$scope.form);
			});
			
			$scope.stripeFormModal = $modal({
				'scope': $scope,
				'controller' : 'stripeController',
				'templateUrl': 'stripe-form-modal',
				'title': 'Subscription',
				'show': false
			});

			$scope.stripeFormModal.$promise.then($scope.stripeFormModal.show);
			
		};

	});


export var hoboStripe = angular.module('hobo.stripe', [])
	.controller('stripeController', function(api, $scope, $window, $rootScope, $modal, Alert, $timeout){
		var hoboStripe = this;
		
		// Set your publishable key: remember to change this to your live publishable key in production
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		
		var stripe = Stripe(STRIPE_PUBLISH_KEY);
		var elements = stripe.elements();

		var style = {
		  base: {
			color: "#32325d",
			fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
			fontSmoothing: "antialiased",
			fontSize: "16px",
			"::placeholder": {
			  color: "#aab7c4"
			}
		  },
		  invalid: {
			color: "#fa755a",
			iconColor: "#fa755a"
		  }
		};

		$scope.cardElement = elements.create("card", { style: style });
		
		$scope.stripe_progress = false;
		
		$scope.$watch('$routeChangeSuccess', function(event) {

			$scope.cardElement.mount("#card-element");
		});
		
		$scope.cardElement.on('change', showCardError);

		function showCardError(event) {
			
			let displayError = document.getElementById('card-errors');
			
			if (event.error) {
				displayError.textContent = event.error.message;
			} else {
				displayError.textContent = '';
			}
		}
		
		$scope.stripeProcess = (form) => {
			
			let displayError = document.getElementById('card-errors');
		
			return stripe.createPaymentMethod({
				type: 'card',
				card: $scope.cardElement,
			})
			.then((result) => {
				
				if (result.error) {
					showCardError(result);
				} else {
					createSubscription({
						user_id: form.user_id,
						customerId: form.customer_id,
						paymentMethodId: result.paymentMethod.id,
						priceId: form.price_id,
					});
				}
			});

		}
		
		/*
			Number 				Brand 					CVC 			Date
			4242424242424242 	Visa 					Any 3 digits 	Any future date
			4000056655665556 	Visa (debit) 			Any 3 digits 	Any future date
			5555555555554444 	Mastercard 				Any 3 digits 	Any future date
			2223003122003222 	Mastercard (2-series) 	Any 3 digits 	Any future date
			5200828282828210 	Mastercard (debit) 		Any 3 digits 	Any future date
			5105105105105100 	Mastercard (prepaid) 	Any 3 digits 	Any future date
			378282246310005 	American Express 		Any 4 digits 	Any future date
			371449635398431 	American Express 		Any 4 digits 	Any future date
			6011111111111117 	Discover 				Any 3 digits 	Any future date
			6011000990139424 	Discover 				Any 3 digits 	Any future date
			3056930009020004 	Diners Club 			Any 3 digits 	Any future date
			36227206271667 		Diners Club (14 digit card) 	Any 3 digits 	Any future date
			3566002020360505 	JCB 					Any 3 digits 	Any future date
			6200000000000005 	UnionPay 				Any 3 digits 	Any future date

			4242424242424242 	tok_us 	pm_card_us 	United States (US) 	Visa
			4000000760000002 	tok_br 	pm_card_br 	Brazil (BR) 	Visa
			4000001240000000 	tok_ca 	pm_card_ca 	Canada (CA) 	Visa
			4000004840008001 	tok_mx 	pm_card_mx 	Mexico (MX) 	Visa
			
			zipcode 75062
			
			4242424242424242 	Succeeds and immediately creates an active subscription.
			4000002760003184 	Requires authentication. confirmCardPayment() will trigger a modal asking for the customer to authenticate. Once the user confirms, the subscription will become active. See manage payment authentication.
			4000008260003178 	Always fails with a decline code of insufficient_funds. See create subscription step on how to handle this server side.
			4000000000000341 	Succeeds when it initially attaches to Customer object, but fails on the first payment of a subscription with the payment_intent value of requires_payment_method. See manage subscription payment failure step.
		*/
		
		function createSubscription({ user_id, customerId, paymentMethodId, priceId }) {
			
			console.log('Create Subs : ', user_id, customerId, paymentMethodId, priceId );
			
			let displayError = document.getElementById('card-errors');
			
			$scope.stripe_progress = true;
			
			return (
				api.call('stripe/createSubscription', {
					user_id: user_id,
					customerId: customerId,
					paymentMethodId: paymentMethodId,
					priceId: priceId,
				})
				.then((result) => {
					$scope.stripe_progress = false;
					console.log('--- Created subscription --- : ', result.data);
					
					if(result.data.error) {
						throw result;
					}
					
					return {
						customerId: customerId,
						paymentMethodId: paymentMethodId,
						priceId: priceId,
						subscription: result['data'],
					};
				})
				// Some payment methods require a customer to be on session
				// to complete the payment process. Check the status of the
				// payment intent to handle these actions.
				.then(handleCustomerActionRequired)
				// If attaching this card to a Customer object succeeds,
				// but attempts to charge the customer fail, you
				// get a requires_payment_method error.
				.then(handlePaymentMethodRequired)
				// No more actions required. Provision your service for the user.
				.then(onSubscriptionComplete)
				.catch((error) => {
					$scope.stripe_progress = false;
					// An error has happened. Display the failure to the user here.
					// We utilize the HTML element we created.
					showCardError(error);
				})
			);
		}
		
		function onSubscriptionComplete(result) {
			$scope.stripe_progress = false;
			
			// Payment was successful.
			console.log('Subscription success : ', result);
			
			if (result.subscription.status === 'active') {
				Alert.success('Subscription Success!');
				$scope.stripeFormModal.$promise.then($scope.stripeFormModal.hide);
				
				// Auto login
				window.location.href = '/';
			}
		}
		
		function handlePaymentMethodRequired({ subscription, paymentMethodId, priceId }) {
			
			console.log("Subscription Handle : ", subscription);
			
			if (subscription.status === 'active') {
			// subscription is active, no customer actions required.
			return { subscription, priceId, paymentMethodId };
			} else if ( subscription.latest_invoice && subscription.latest_invoice.payment_intent.status === 'requires_payment_method') {
			// Using localStorage to manage the state of the retry here,
			// feel free to replace with what you prefer.
			// Store the latest invoice ID and status.
			localStorage.setItem('latestInvoiceId', subscription.latest_invoice.id);
			// localStorage.setItem('latestInvoicePaymentIntentStatus', subscription.latest_invoice.payment_intent.status);
			throw { error: { message: 'Your card was declined.' } };
			} else {
			return { subscription, priceId, paymentMethodId };
			}
		}
		
		function cancelSubscription() {
			api.call('stripe/cancelSubscription', {
				subscriptionId: subscriptionId,
			})
			.then(response => {
				return response.json();
			})
			.then(cancelSubscriptionResponse => {
				// Display to the user that the subscription has been cancelled.
			});
		}
		
		function handleCustomerActionRequired({
		  subscription,
		  invoice,
		  priceId,
		  paymentMethodId,
		  isRetry,
		}) {
		  if (subscription && subscription.status === 'active') {
			// Subscription is active, no customer actions required.
			return { subscription, priceId, paymentMethodId };
		  }

		  // If it's a first payment attempt, the payment intent is on the subscription latest invoice.
		  // If it's a retry, the payment intent will be on the invoice itself.
		  let paymentIntent = invoice ? invoice.payment_intent : subscription.latest_invoice.payment_intent;

		  if (paymentIntent.status === 'requires_action' || (isRetry === true && paymentIntent.status === 'requires_payment_method')) {
			return stripe
			  .confirmCardPayment(paymentIntent.client_secret, {
				payment_method: paymentMethodId,
			  })
			  .then((result) => {
				if (result.error) {
				  // Start code flow to handle updating the payment details.
				  // Display error message in your UI.
				  // The card was declined (i.e. insufficient funds, card has expired, etc).
				  throw result;
				} else {
				  if (result.paymentIntent.status === 'succeeded') {
					// Show a success message to your customer.
					// There's a risk of the customer closing the window before the callback.
					// We recommend setting up webhook endpoints later in this guide.
					return {
					  priceId: priceId,
					  subscription: subscription,
					  invoice: invoice,
					  paymentMethodId: paymentMethodId,
					};
				  }
				}
			  })
			  .catch((error) => {
				displayError(error);
			  });
		  } else {
			// No customer action needed.
			return { subscription, priceId, paymentMethodId };
		  }
		}
		/*
		function retryInvoiceWithNewPaymentMethod(
		  customerId,
		  paymentMethodId,
		  invoiceId,
		  priceId
		) {
		  return (
			fetch('/retry-invoice', {
			  method: 'post',
			  headers: {
				'Content-type': 'application/json',
			  },
			  body: JSON.stringify({
				customerId: customerId,
				paymentMethodId: paymentMethodId,
				invoiceId: invoiceId,
			  }),
			})
			  .then((response) => {
				return response.json();
			  })
			  // If the card is declined, display an error to the user.
			  .then((result) => {
				if (result.error) {
				  // The card had an error when trying to attach it to a customer.
				  throw result;
				}
				return result;
			  })
			  // Normalize the result to contain the object returned by Stripe.
			  // Add the addional details we need.
			  .then((result) => {
				return {
				  // Use the Stripe 'object' property on the
				  // returned result to understand what object is returned.
				  invoice: result,
				  paymentMethodId: paymentMethodId,
				  priceId: priceId,
				  isRetry: true,
				};
			  })
			  // Some payment methods require a customer to be on session
			  // to complete the payment process. Check the status of the
			  // payment intent to handle these actions.
			  .then(handlePaymentThatRequiresCustomerAction)
			  // No more actions required. Provision your service for the user.
			  .then(onSubscriptionComplete)
			  .catch((error) => {
				// An error has happened. Display the failure to the user here.
				// We utilize the HTML element we created.
				displayError(error);
			  })
		  );
		}
		*/
	});