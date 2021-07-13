


angular.module('hoboAdmin.bookings', [])

	.controller('BookingsController', function($scope, api, Alert, searchAirports) {

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
			var url = 'admin/bookings/get';
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
		}
		getBookings();

		$scope.$on('bookings:refresh', () => {
			getBookings();
		});

		$scope.paginate = (page) => {
			bookings.currentPage = page;
			var url = bookings.list.pagination[page];
			getBookings(url);
		};



		$scope.$watch('[sort.from, sort.to]', () => {

			if($scope.sort.from != null && $scope.sort.to != null){
				$scope.filter();
			}

		});

		$scope.filter = () => {


			var url = bookings.list.pagination[bookings.currentPage];

			var filter = {
				from:  moment($scope.sort.from).format('YYYY-MM-DD'),
				to:  moment($scope.sort.to).format('YYYY-MM-DD')
			};

			getBookings(url, filter);
		}


		$scope.approveModal = (booking_id) => {
			$scope.$broadcast('booking:approve', booking_id);
		};

		$scope.infoModal = (booking_id) => {
			$scope.$broadcast('booking:info', booking_id);
		};

		$scope.searchAirports = (name) => {

			return searchAirports.search(name).then(function(response){
				return response;
			})

		};


	})



	.directive('bookingApprove', function($modal, api, Alert){

		return {

			link: function($scope, $elem, args){

				var modal = null;

				$scope.booking = {
					arriving_date: null,
					arriving_time: null,
					departure_time_final: null,
					tail_number: null,
					fbo: null,
					booking_id: null,
					flight_id: null,
					note: ''
				};
				var empty = $scope.booking;

				$scope.$on('booking:approve', function(e, booking_id){
					$scope.booking = angular.copy(empty);

					modal = $modal({
						scope: $scope,
						templateUrl: 'bookings.modal.approve.html',
						title: 'Approve booking',
						show: false
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

				$scope.acceptBooking = (booking, booking_id, flight_id) => {

					booking.booking_id = booking_id;
					booking.flight_id = flight_id;

					booking.arriving_date = moment(booking.arriving_date).format('YYYY-MM-DD');
					booking.arriving_time = moment(booking.arriving_time).format('HH:mm');
					booking.departure_time_final = moment(booking.departure_time_final).format('HH:mm');

					var error = false;
					if(booking.arriving_date == 'Invalid date'){
						Alert.info('Please select valid arriving date');
						error = true;
					}

					if(booking.arriving_time == 'Invalid date'){
						Alert.info('Please select valid arriving time');
						error = true;
					}
					if(booking.departure_time_final == 'Invalid date'){
						Alert.info('Please select valid departure time');
						error = true;
					}

					if(booking.tail_number == null){
						Alert.info('Please enter tail number');
						error = true;
					}
					if(booking.fbo == null){
						Alert.info('Please enter FBO');
						error = true;
					}

					if(error){
						return;
					}

					api.call('admin/bookings/accept', booking)
						.success(()=>{
							Alert.info('Booking approved');
							$scope.$broadcast('bookings:refresh');
							modal.hide();
						})

				};


				$scope.declineBooking = (booking_id, note) => {

					if(note == ''){
						Alert.info('Please enter note');
						return;
					}

					api.call('admin/bookings/decline', {
						booking_id: booking_id,
						note: note
					})
						.success((res)=>{
							if(res.status == 1){
								Alert.info('Booking declined');
								$scope.$broadcast('bookings:refresh');
								modal.hide();
							}else{
								Alert.info(res.messages.message);

							}
						});

				};

			}

		}

	})

	.directive('bookingInfo', function($modal, api){
		return {

			link: function($scope, $elem, args){

				var modal = null;
				$scope.booking_info = null;

				$scope.$on('booking:info', function(e, booking_id){

					modal = $modal({
						scope: $scope,
						templateUrl: 'bookings.modal.info.html',
						title: 'Booked flight info',
						show: false
					});

					api.call('admin/booking/get/details', {
							booking_id: booking_id
						})
						.success((res) => {

							modal.$scope.booking_info = res.responseData;

							modal.$promise.then(modal.show);
						})
					$scope.$on('modal.hide', () => {
						modal = null;
					});


				})




			}

		}

	});