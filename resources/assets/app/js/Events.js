export var events = angular.module('hobo.events', 
	['ngRoute',
	'ngSanitize',
	'mgcrea.ngStrap',
	'ngFileUpload',])
	.filter('trustAsHtml', ['$sce', function($sce){
	  return function(text) {
		return $sce.trustAsHtml(text);
	  };
	}])
	.controller('EventsController', function(api, $scope, $sce, $rootScope, $modal, Alert){
		var events = this;

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

		$scope.applyevents = (event) => {
			$scope.errors = {};
			
			console.log($rootScope.user);
			
			modal = $modal({
				'scope': $scope,
				'templateUrl': 'reverse-event-modal.html',
				'title': 'Add Event',
				'show': false
			});
			
			modal.$scope.form = {
				event_id : event.id,
				first_name : $rootScope.user.first_name,
				last_name : $rootScope.user.last_name,
				phone : $rootScope.user.phone,
				email : $rootScope.user.email,
			};

			modal.$promise.then(modal.show);
		}
		
		$scope.reserveEvent = (form) => {
			
			$scope.errors = {};
			var stop_submit = false;
			
			if(!form.first_name){
				Alert.info('Please enter first name');
				$scope.errors['first_name'] = 'Please enter first name';
				stop_submit = true;
			}

			if(!form.last_name){
				Alert.info('Please enter last name');
				$scope.errors['last_name'] = 'Please enter last name';
				stop_submit = true;
			}

			if(!form.email){
				Alert.info('Please enter email');
				$scope.errors['email'] = 'Please enter email';
				stop_submit = true;
			}

			if(!form.phone){
				Alert.info('Please enter phone number');
				$scope.errors['phone'] = 'Please enter phone number';
				stop_submit = true;
			}
			

			if(stop_submit){
				return false;
			}
			
			var data = {
				'user_id' : $rootScope.user.user_id,
				'event_id': form.event_id,
				'first_name': form.first_name,
				'last_name': form.last_name,
				'email' : form.email,
				'phone' : form.phone,
				'seats_count' : form.seats_count

			};
			console.log("Event Reseveration Data : ", data);
			
			api.call('reservation', data)
			.success((res) => {
				console.log(res);
				modal.hide();
				Alert.success('Event Reserved!');
			});
			
		}

		$scope.sendevents = () => {
			api.call('contact/apply-events', $scope.form)
				.success((res) => {
					if(res.status == 1){
						Alert.info(res.responseData.message);
						modal.$promise.then(modal.hide)
					}else{
						$scope.errors = res.messages;
					}
				});
		}

		$scope.send = () => {
			api.call('contact/events', $scope.form)
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

		}
		


	});
