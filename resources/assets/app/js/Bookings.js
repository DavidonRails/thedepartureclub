export var bookings = angular.module('hobo.bookings', [])
	.controller('BookingsController', function(api, $scope, $modal, $rootScope){

		var modal;

		$scope.flight_details = [];

		$scope.bookingDetails = (booking_id) => {

			api.call('booking/get/details', {
				booking_id: booking_id
			})
				.success((res) => {


					if(res.status == 1){

						modal = $modal({
							scope: $scope,
							templateUrl: 'trip-details.html',
							show: false
						});

						modal.$scope.booking = res.responseData;

						$scope.flight.booking_id = res.responseData.booking_id;
						$scope.flight_details = res.responseData;
						$scope.flight_details.seats = res.responseData.flight_passengers_count;
						$scope.flight_details.origin_location = res.responseData.flight_origin;
						$scope.flight_details.destination_location = res.responseData.flight_destination;
						$scope.flight_details.flight_start_human = res.responseData.departure_date_human;
						$scope.flight_details.price = res.responseData.flight_price_total;

						console.log(res.responseData);

						modal.$promise.then(modal.show);

					}

				})

		};

		$scope.flight = {
			booking_id: null,
			passangers: [],
		}



		$scope.finishBooking = () => {

			$scope.step('passengers');

		};


		$scope.step = (step) => {


			switch (step) {

				case 'passengers':
					engine.passengers();
					break;
				case 'checkout':
					if(validate.passengers() == false){
						return;
					}
					engine.checkout();
					break;
				default:
					engine.passengers();
					break;

			}

		};

		$scope.passengersTab = 'list';


		if(user){
			$scope.flight.passangers.push({
				first_name: $rootScope.user.first_name,
				last_name: $rootScope.user.last_name,
				weight: null,
				dob: null
			});
		}


		var engine = {

			passengers: () => {

				modal.$promise.then(modal.hide);

				modal = $modal({
					scope: $scope,
					templateUrl: 'passenger-modal.html',
					show: false
				});

				$scope.passengers_form = {
					first_name: '',
					last_name: '',
					dob: '',
					weight: '',
					type: 'add'
				};
				var passengers_form_clean = angular.copy($scope.passengers_form);

				$scope.passengersTabAction = (tab) => {
					$scope.passengersTab = tab;
					$scope.error = null;
					$scope.passengers_form = passengers_form_clean;

				}
				$scope.addPassenger = function(form) {


					if($scope.flight.passangers.length == $scope.flight_details.seats){
						$scope.error = 'You have maximum number of passengers for flight';
						return;
					}

					if(!form.$valid){
						$scope.error = 'All passengers must have first name, last name, weight and date of birth';
						return;
					}

					$scope.flight.passangers.push({
						first_name: form.first_name.$viewValue,
						last_name: form.last_name.$viewValue,
						weight: form.weight.$viewValue,
						birth_date: form.dob.$viewValue
					});

					$scope.passengers_form = passengers_form_clean;
					$scope.passengersTab = 'list';

				};



				$scope.removePassenger = (index) => {
					$scope.flight.passangers.splice(index, 1);
				}

				$scope.editPassenger = (passenger, index) => {

					$scope.passengers_form.first_name = passenger.first_name;
					$scope.passengers_form.last_name = passenger.last_name;
					if(passenger.birth_date != null){
						$scope.passengers_form.dob = moment(passenger.birth_date).toDate();
					}else{
						$scope.passengers_form.dob = null;
					}
					if(passenger.weight != null){
						$scope.passengers_form.weight = parseInt(passenger.weight);
					}else{
						$scope.passengers_form.weight = null;
					}
					$scope.passengers_form.type = 'edit';
					$scope.passengersTab = 'form';
					$scope.error = null;
					$scope.index = index;

				}

				$scope.updatePassenger = (passenger) => {

					if(!passenger.$valid){
						$scope.error = 'All passengers must have first name, last name, weight and date of birth';
						return;
					}

					$scope.flight.passangers[$scope.index].first_name = passenger.first_name.$viewValue;
					$scope.flight.passangers[$scope.index].last_name = passenger.last_name.$viewValue;
					$scope.flight.passangers[$scope.index].weight = passenger.weight.$viewValue;
					$scope.flight.passangers[$scope.index].birth_date = passenger.dob.$viewValue;
					$scope.passengersTab = 'list';
					$scope.error = null;


				}

				modal.$promise.then(modal.show);




			},

			checkout: function(){
				
				modal.$promise.then(modal.hide);

				$scope.passengers_lenght = $scope.flight.passangers.length;

				modal = $modal({
					scope: $scope,
					templateUrl: 'flight-itinerary.html',
					show: false
				});


				$scope.checkout = () => {

					var form = angular.element(document.getElementById('paypalbookform'));

					angular.element(document.querySelector('[name="item_name"]')).val(
						$scope.flight_details.origin_location +
						' - ' +
						$scope.flight_details.destination_location
					);

					api.call('booking/custom/pay/web', $scope.flight)
						.success((res) => {
							if(res.status == 1){
								form.append(
									'<input type="hidden" name="custom" value="cb:' + $scope.flight_details.booking_id + '">' +
									'<input type="hidden" name="amount" value="' + $scope.flight_details.price + '">'
								);
								setTimeout(function () {
									form.submit();
								}, 100)
							}else{
								$scope.error = res.messages.message;
							}
						})

				}

				modal.$promise.then(modal.show);

			}
		};

		var validate = {
			passengers: () => {

				if($scope.flight.passangers.length == 0){
					$scope.error = 'Flight must have min one passenger';
					return false;
				}

				var valid = true;
				angular.forEach($scope.flight.passangers, (i) => {
					if(!i.first_name){
						valid = false;
					}
					if(!i.last_name){
						valid = false;
					}
					if(!i.weight){
						valid = false;
					}
					if(!i.birth_date){
						valid = false;
					}
				})

				if(valid == false){

					$scope.error = 'All passengers must have first name, last name, weight and date of birth';

				}
				return valid;

			}
		}

	})