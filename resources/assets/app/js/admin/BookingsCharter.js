


angular.module('hoboAdmin.bookingsCharter', [])
	.controller('BookingsCharterController', function($scope, api, Alert, searchAirports) {

		var bookings = this;

		bookings.currentPage = 0;
		bookings.list = {
			data: [],
			pagination: []
		}
		$scope.sort = {
			from: null,
			to: null
		};


		var getBookings = (paginate, filter) => {
			var url = 'admin/bookings/get/custom';
			if(typeof paginate != 'undefined'){
				url = paginate;
			}
			if(paginate == ''){
				return false;
			}
			api.call(url, filter)
				.success((res) => {
					if(res.status == 1) {
						bookings.list.data = res.responseData.data;
						bookings.list.pagination = res.responseData.pagination;
					}
				})
		};
		getBookings();


		$scope.approve = (booking_id) => {
			$scope.$broadcast('charter:approve', booking_id);

		};

		$scope.info = (booking_id) => {
			$scope.$broadcast('charter:info', booking_id);
		}

		$scope.finish = (booking_id) => {
			$scope.$broadcast('charter:finish', booking_id);
		}

	})

	.directive('charterInfo', function(api, $modal){

		return {
			link: function($scope, $elem, args){

				var modal = null;

				$scope.$on('charter:info', (e, booking_id)=>{
					$scope.errors = {};

					modal = $modal({
						scope: $scope,
						templateUrl: 'bookings-charter.modal.info.html',
						title: 'Charter booking info',
						show: false
					});


					api.call('admin/booking/get/details', {
							booking_id: booking_id
						})
						.success((res) => {

							// modal.$scope.booking_info = res.responseData;

							console.log(res);

							modal.$promise.then(modal.show);
						});

				})


			}
		}


	})

	.directive('charterApprove', function(api, $modal, searchAirports){

		return {
			link: function($scope, $elem, args){

				var modal = null;

				$scope.form = {
					origin_airport: '',
					origin_airport_data: '',
					destination_airport: '',
					destination_airport_data: '',
					final_price: '',
					arriving_date: '',
					arriving_time: '',
					tail_number: '',
					fbo: '',
					note: ''
				};
				var empty = $scope.form;

				$scope.errors = {};


				$scope.searchAirports = (string) => {

					return searchAirports.search(string);

				};

				$scope.$on('charter:approve', (e, booking_id) => {

					modal = $modal({
						scope: $scope,
						templateUrl: 'bookings-charter.modal.approve.html',
						title: 'Charter booking info',
						show: false
					});


					modal.$scope.$watch('form.origin_airport', function(){
						if(modal.$scope.form.origin_airport.length == 3){
							$scope.searchAirports(modal.$scope.form.origin_airport).then(function(response){
								modal.$scope.form.origin_airport_data = response;
							});
						}
					});

					modal.$scope.$watch('form.destination_airport', function(){
						if(modal.$scope.form.destination_airport.length == 3){
							$scope.searchAirports(modal.$scope.form.destination_airport).then(function(response){
								modal.$scope.form.destination_airport_data = response;
							});
						}
					});

					api.call('admin/booking/get/details', {
							booking_id: booking_id
						})
						.success((res) => {

							modal.$scope.booking_info = res.responseData;

							modal.$promise.then(modal.show);
						});


					$scope.$on('modal.hide', () => {
						modal = null;
					});
					
				});

				$scope.$on('charter:finish', (e, booking_id) => {

					modal = $modal({
						scope: $scope,
						templateUrl: 'bookings-charter.modal.finish.html',
						title: 'Charter booking info',
						show: false
					});
					
					api.call('admin/booking/get/details', {
							booking_id: booking_id
						})
						.success((res) => {

							modal.$scope.booking_info = res.responseData;

							modal.$promise.then(modal.show);
						});


				});


				$scope.accept = (booking_id, booking, return_flight) => {

					if(typeof booking.aircraft != 'undefined'){
						booking.aircraft_id = booking.aircraft.aircraft_id;
						booking.aircraft_image_id = booking.aircraft.aircraft_image_id;
					}
					booking.booking_id = booking_id;
					booking.return_flight = return_flight;
					api.call('admin/booking/charter/offer', booking)
						.success((res) => {
							if(res.status == 1){
								if(form.aircraft_custom_image){
									upload(form.aircraft_custom_image, form.aircraft_id, res.responseData.flight_id)
								}
								modal.hide();
								Alert.success('Flight added');
								$scope.$broadcast('flights:refresh');
							}
							if(res.status == 0){
								$scope.errors = res.messages;
							}
						})

				};

				$scope.charterApprove = (booking_id) => {

					api.call('admin/booking/charter/approve', {
						booking_id: booking_id
 					})

				};

				$scope.charterDecline = (booking_id) => {

					api.call('admin/booking/charter/decline', {
						booking_id: booking_id
					})

				};


			}
		}


	});

