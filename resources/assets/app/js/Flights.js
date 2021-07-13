export var flights = angular.module('hobo.flights', ['mgcrea.ngStrap'])
	.controller('FlightsController', function(api, $scope, $rootScope, Alert){

		var flights = this;

		flights.list = {
			data: [],
			pagination: []
		};
		
		flights.noMoreLoad = false;
		
		if($rootScope.user) {
			flights
		}

		if(typeof no_results != 'undefined'){
			flights.noMoreLoad = true;
		}

		if(typeof fp != 'undefined'){
			flights.list.pagination = JSON.parse(fp);
		}

		var getFlights = (paginate, filter) => {

			var url = 'flights/get';
			if(typeof paginate != 'undefined'){
				url += '/?page=' + paginate;
			}

			if(paginate == ''){
				return false;
			}

			api.call(url, {
				'per_page': 6
			})
			.success((res) => {
				if(res.status == 1) {

					angular.forEach(res.responseData.data, function(flight){
						flights.list.data.push(flight)
					})

					flights.list.pagination = res.responseData.pagination;

					if(flights.list.pagination.next_page == null){
						flights.noMoreLoad = true;
					}

				}
			});

		};




		$scope.loadMore = () => {

			if(!flights.list.pagination.next_page){
				flights.noMoreLoad = true;
				return false;
			}

			var page = flights.list.pagination.next_page.match(/(?:[page=])(\d+)/)[0].replace('=', '');
			getFlights(page);

		};

		$scope.bookFlight = (flight_id) => {
			
			$scope.discount = 0;
			$scope.show_discount = 0;
			
			if($rootScope.user.package) {
				$scope.discount = parseFloat($rootScope.user.package.discount);
				if($scope.discount > 0)  $scope.show_discount = 1;
			}
			
			
			/*
				[{
					"flight_identification":"",
					"hash":"",
					"flight_start":"2020-06-12",
					"route_origin":"Jackson, WY",
					"route_destination":"Scottsdale,AZ",
					"flight_time":"7",
					"active":1,
					"status":1,
					"price":1599,
					"seats":"6",
					"date":"2020-06-12",
					"period_from":"12:00",
					"period_to":"19:00",
					"aircraft_name":"Light Jet",
					"aircraft_image":"https:\/\/staging.thedepartureclub.com\/data\/images\/aircrafts\/c74d97b01eae257e44aa9d5bade97baf\/light-jet.jpg",
					"flight_id":1606, 
					"until_takeoff":1,
					"flight_start_human":"Jun 12, 2020",
					"flight_start_short":"Jun 1111"
				}]
			*/
			
			if($rootScope.auth == false){
				$scope.$emit('auth', 'login');
			} else {
				api.call('membership', {
					user_id: $rootScope.user.user_id,
				})
				.success((res) => {
					var billing_history = res.responseData;
					var billing_history_count = billing_history.length;
					if(billing_history_count > 0 && billing_history[0].status == 1) {
						
						// set discount?
						$scope.$broadcast('flight:book', flight_id);
						
					} else {
						
						alert("Please upgrade to a paid membership");
						window.location.href = 'membership';
						
					}

				});
			}
		}
		
		
		$scope.createAlert = (data) => {

			console.log(data);

			api.call('alerts/add', {
				origin: data.origin_location_input.formatted_address,
				origin_longitude: data.origin_location_input.geometry.lng,
				origin_latitude: data.origin_location_input.geometry.lat,
				destination: data.destination_location_input.formatted_address,
				destination_longitude: data.destination_location_input.geometry.lng,
				destination_latitude: data.destination_location_input.geometry.lat
			})
				.success((res) => {
					if(res.status == 1){
						Alert.info(res.responseData.message);
					}
				});
		}

	})

	.directive('flightSearch', function(api, citySearch, Alert) {

		return {
			restrict: 'A',
			link: function($scope, element, attr){

				$scope.slider_price = {
					minValue: 0,
					maxValue: 5000,

					options: {
						floor: 0,
						ceil: 5000,
						hideLimitLabels: true,
						translate: function(value, sliderId, label) {
							switch (label) {
								case 'model':
									return '$' + value;
								case 'high':
									return '$' + value;
								default:
									return '$' + value
							}
						}
					}
				};

				$scope.$watch('slider_price',function(){
					$scope.range_price = '$'+$scope.slider_price.minValue + ' - $' + $scope.slider_price.maxValue;
				},true);

				$scope.citySearch = (value) => {

					if(typeof value == 'object'){
						return false;
					}

					return citySearch.search(value).then(function(response){
						return response;
					});

				};

				$scope.search = ($event) => {

					if(typeof $scope.origin_location == 'undefined') {
						Alert.info('Please enter origin destination');
						$event.preventDefault();
						return;
					}

					angular.element(document.querySelector('[name="origin_location_input"]')).val(JSON.stringify($scope.origin_location));
					angular.element(document.querySelector('[name="destination_location_input"]')).val(JSON.stringify($scope.destination_location));
					angular.element(document.querySelector('[name="price_from"]')).val($scope.slider_price.minValue);
					angular.element(document.querySelector('[name="price_to"]')).val($scope.slider_price.maxValue);


				}

			}

		};

	})

	.directive('flightDetails', function(){
		//var discounted_price = $flight.price - ($flight.price * $flight.discount);
		
		return {
			restrict: 'A',
			template: `
			<a href="javascript:void(0)" class="product-card"  ng-click="bookFlight(flight.flight_id)">
				<article>
					<div class="product-badge">
						<div class="days">{{ flight.until_takeoff }} DAYS</div>
						<div class="until">UNTIL TAKEOFF</div>
					</div>
					<div class="product-img">
						<img src="{{flight.aircraft_image}}" alt="">
						<ul class="from-to">
							<li class="from">
								<span>
									<img src="images/ico/origin-ico.svg" alt="">
									{{flight.route_origin}}
								</span>
							</li>
							<li class="to">
								<span>
									<img src="images/ico/destination-ico.svg" alt="">
									{{flight.route_destination}}
								</span>
							</li>
						</ul>
					</div>
					<div class="product-info">
						<table>
							<tr>
								<td class="data-cell">
									<div class="data">
										<span>{{flight.flight_start_human}}</span>
										<small>DEPARTING</small>
									</div>
								</td>
								<td class="data-cell seats">
									<div class="data">
										<span>{{flight.seats}}</span>
										<small>SEATS</small>
									</div>
								</td>
								<td class="data-cell">
									<div class="data">
										  <span>TEST $ {{ flight.discount }} 
			                                                                   (<font style="font-size:12px; text-decoration: line-through; color:gray;">$ {{ flight.price }}</font>)
			                                                          </span>
										<small>FOR FULL JET</small>
									</div>
								</td>
							</tr>
						</table>
						<button class="btn-angle">
							<span class="ti-angle-right"></span>
						</button>
					</div>
				</article>
			</a>
			`
		}


	})

	.factory('citySearch', function(api, $q){


		return {
			search: function(query){
				var defer = $q.defer();

				if(typeof query == 'object'){
					defer.resolve({
						data: [query]
					});
					return defer.promise;
				}

				if(query.length < 3){
					defer.reject('Short');
				}else{
					api.call('cities/search', {
							search: query
						})
						.success(function(response){
							if(response.status == 1){
								defer.resolve(response.responseData);
							}
						})
						.error(function(){
							defer.reject('Error')
						});
				}

				return defer.promise;

			},
			cancel: function () {
				defer.resolve('New Search');
			}
		}

	})

	.directive('bookFlight', function(api, $modal, $rootScope, Alert){
	
		return {

			link: function ($scope, elem, attr) {

				var modal = null;

				$scope.error = null;

				$scope.flight = {
					flight_id: null,
					departure_start: null,
					departure_end: null,
					passangers: []
				};
				var flight_clean = $scope.flight;

				$scope.flight_details = {};

				$scope.time_period = {
					min_time: null,
					max_time: null
				};

				$scope.passengersTab = 'list';

				$scope.$on('flight:book', (e, flight_id)=>{
					$scope.flight = angular.copy(flight_clean);
					
					$scope.flight.flight_id = flight_id;

					$scope.step();
				});
				
				
				$scope.max_date = new Date();
				$scope.number_of_passengers = '';
				$scope.step = (step) => {
					$scope.error = null;

					switch (step) {

						case 'open-submit':
							engine.openSubmitModal();
							break;
						case 'submit-request':
							if(!$scope.flight_details.number_of_passengers){
								$scope.error = "Please input number of passengers";
								return;
							}
							console.log($scope.flight_details.number_of_passengers);
							console.log(parseInt($scope.flight_details.seats));
							if ($scope.flight_details.number_of_passengers > parseInt($scope.flight_details.seats)) {
								$scope.error = "Available seats are " + $scope.flight_details.seats;
								return;
							}

							modal.$promise.then(modal.hide);
							engine.sendEmail()
							break;

						case 'time':
							engine.timeSelect();
							break;

						default:
							engine.flightDetails($scope.flight.flight_id);
							break;

					}

				};



				if(user){
					$scope.flight.passangers.push({
						first_name: $rootScope.user.first_name,
						last_name: $rootScope.user.last_name,
						weight: null,
						dob: null
					});
				}


				var engine = {
					openSubmitModal: () => {
						modal.$promise.then(modal.hide);

						modal = $modal({
							scope: $scope,
							templateUrl: 'submit-modal.html',
							show: false
						});

						modal.$promise.then(modal.show);
					},
					flightDetails: (flight_id) => {
						modal = $modal({
							scope: $scope,
							templateUrl: 'flight-details.html',
							show: false
						});

						modal.$scope.$watch('time_start', (newValue) => {
							$scope.flight.departure_start = newValue;
						});
						modal.$scope.$watch('time_end', (newValue) => {
							$scope.flight.departure_end = newValue;
						});

						api.call('flights/get/details', {
							flight_id: flight_id
						}).success((res) => {
							$scope.flight_details = res.responseData;
							
							modal.$scope.flight_details = res.responseData;

							var start_time = moment(moment().format('YYYY-MM-DD') + ' ' + modal.$scope.flight_details.period_from + ':00').toDate();
							var end_time = moment(moment().format('YYYY-MM-DD') + ' ' + modal.$scope.flight_details.period_to + ':00').toDate();

							modal.$scope.time_period = {
								min_time: start_time,
								max_time: end_time
							};

							modal.$scope.time_start = start_time;
							modal.$scope.time_end = end_time;
							modal.$scope.flight_details.show_discount = $scope.show_discount;
							
							modal.$scope.flight_details.discount_price = $scope.flight_details.price - ($scope.flight_details.price * parseFloat($scope.discount));
							console.log("User Discount Show : ", modal.$scope.flight_details);
							modal.$promise.then(modal.show);
						})

					},

					timeSelect: () => {
						modal.$promise.then(modal.hide);

						modal = $modal({
							scope: $scope,
							templateUrl: 'flight-details.html',
							show: false
						});

						modal.$scope.$watch('time_start', (newValue) => {
							$scope.flight.departure_start = newValue;
						});
						modal.$scope.$watch('time_end', (newValue) => {
							$scope.flight.departure_end = newValue;
						});

						var start_time = moment(moment().format('YYYY-MM-DD') + ' ' + $scope.flight_details.period_from + ':00').toDate();
						var end_time = moment(moment().format('YYYY-MM-DD') + ' ' + $scope.flight_details.period_to + ':00').toDate();

						modal.$scope.time_period = {
							min_time: start_time,
							max_time: end_time
						};

						modal.$scope.time_start = start_time;
						modal.$scope.time_end = end_time;

						modal.$promise.then(modal.show);

					},

					passengers: () => {

						modal.$promise.then(modal.hide);

						modal = $modal({
							scope: $scope,
							templateUrl: 'passenger-modal.html',
							show: false
						});

						var passengers_form_clean= {
							first_name: '',
							last_name: '',
							dob: '',
							weight: '',
							type: 'add'
						};
						$scope.passengers_form = angular.copy(passengers_form_clean);

						$scope.passengersTabAction = (tab) => {
							$scope.passengersTab = tab;
							$scope.error = null;
							angular.copy(passengers_form_clean, $scope.passengers_form);

						}
						$scope.addPassenger = function(form) {

							if(!moment(form.dob.$viewValue).isValid()){
								$scope.error = 'Please enter valid birthdate in format MM/DD/YYYY';
								return;
							}

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
							$scope.error = null;
							angular.copy(passengers_form_clean, $scope.passengers_form);
							$scope.passengersTab = 'list';

						};



						$scope.removePassenger = (index) => {
							$scope.flight.passangers.splice(index, 1);
						}

						$scope.editPassenger = (passenger, index) => {

							$scope.passengers_form.first_name = passenger.first_name;
							$scope.passengers_form.last_name = passenger.last_name;
							if(passenger.birth_date != null){
								$scope.passengers_form.dob = moment(passenger.birth_date).format('L');
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

							if(!moment(passenger.dob.$viewValue).isValid()){
								$scope.error = 'Please enter valid birthdate in format MM/DD/YYYY';
								return;
							}


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
					sendEmail: function() {
						api.call('booking/web/sendEmail', $scope.flight_details).success((res) => {
							Alert.info('Thank you for your booking request. We will reach out to you with a response soon.');
						});
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

							api.call('booking/web/add', $scope.flight)
								.success((res) => {
									if(res.status == 1){
										form.append(
											'<input type="hidden" name="custom" value="b:' + res.responseData.pending_id + '">' +
											'<input type="hidden" name="amount" value="' + res.responseData.price + '">'
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


			}

		}

	})
	.filter('moment_filter', function () {
		return function (value,format) {
			return moment(value).format(format||'HH:mm')
		};
	});
