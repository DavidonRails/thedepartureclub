export var charterFlight = angular.module('hobo.charterFlight', [])

	.controller('CharterFlightController', function (api, $scope, $modal, Alert, $rootScope) {

		var modal = null;

		$scope.charter_flight = {
			origin_location: null,
			origin_lat: null,
			origin_lon: null,
			destination_location: null,
			destination_lat: null,
			destination_lon: null,
			aircraft_id: null,
			return: null,
			flight_date_start: null,
			flight_date_end: null,
			passengers_count: null
		};

		$scope.aircrafts = [];

		$scope.errors = {};

		$scope.min_date = new Date();


		$scope.request = () => {

			modal = $modal({
				scope: $scope,
				templateUrl: 'charter-flight-modal.html'
			})

			setTimeout(function(){
				modal.$promise.then(modal.show);
			}, 200)
		};
		$scope.request();
		
		

		$scope.return = false;

		$scope.returnFlight = (status) => {
			$scope.return = status;
		}

		$scope.getAirplanes = (form) => {
			$scope.errors = {};


			if($rootScope.auth == false){
				$scope.$emit('auth', 'login');
				return;
			}



			if(typeof form.destination_location == 'undefined'){
				$scope.errors.destination = 'Please enter destination';
			}

			if (typeof form.destination_location.formatted_address == 'undefined') {
				$scope.errors.destination = 'Please select valid address';
			}

			if(typeof form.origin_location == 'undefined'){
				$scope.errors.origin = 'Please enter origin';
			}
			
			if (typeof form.origin_location.formatted_address == 'undefined') {
				$scope.errors.origin = 'Please select valid address';
			}

			if(typeof form.flight_date_start_date == 'undefined' && typeof form.flight_date_start_time == 'undefined'){
				$scope.errors.date_start = 'Please enter departure date and time'
			}


			if(form.return == 1) {
				if(typeof form.flight_date_end_date == 'undefined' && typeof form.flight_date_end_time == 'undefined'){
					$scope.errors.date_end = 'Please enter return flight departure date and time'
				}
			}

			if(Object.keys($scope.errors).length){
				return;
			}

			if($scope.return){
				$scope.charter_flight.return = 1;
			} else {
				$scope.charter_flight.return = 0;
			}


			$scope.charter_flight.origin_location = form.origin_location.formatted_address;
			$scope.charter_flight.origin_lat = form.origin_location.geometry.lat;
			$scope.charter_flight.origin_lon = form.origin_location.geometry.lng;

			$scope.charter_flight.destination_location = form.destination_location.formatted_address;
			$scope.charter_flight.destination_lat = form.destination_location.geometry.lat;
			$scope.charter_flight.destination_lon = form.destination_location.geometry.lng;

			var start_date = moment(form.flight_date_start_date).format('YYYY-MM-DD');
			var start_time = moment(form.flight_date_start_time).format('HH:mm:ss');

			$scope.charter_flight.flight_date_start = start_date + ' ' + start_time;

			if($scope.return){

				var end_date = moment(form.flight_date_end_date).format('YYYY-MM-DD');
				var end_time = moment(form.flight_date_end_time).format('HH:mm:ss');

				$scope.charter_flight.flight_date_end = end_date + ' ' + end_time;
			}

			$scope.charter_flight.passengers_count = form.passengers_count;


			api.call('flight/request', {
				origin_lat: $scope.charter_flight.origin_lat,
				origin_lon: $scope.charter_flight.origin_lon,
				destination_lat: $scope.charter_flight.destination_lat,
				destination_lon: $scope.charter_flight.destination_lon,
				return: $scope.charter_flight.return
			})
				.success((res) => {
					if(res.status == 1){

						$scope.aircrafts = res.responseData;
						modal.$promise.then(modal.hide);

					}
				});
			

		}


		$scope.requestFlight = (aircraft_id) => {

			$scope.charter_flight.aircraft_id = aircraft_id;

			api.call('booking/custom', $scope.charter_flight)
				.success((res) => {
					$scope.aircrafts = [];
					Alert.info('Your request is submitted.')
				});


		}



	});
