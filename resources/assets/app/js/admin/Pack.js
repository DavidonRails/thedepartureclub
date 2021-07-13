


angular.module('hoboAdmin.pack', [])
.controller('PackController', function($scope, api, searchLocation, Alert) {
	var pack = this;
	pack.list = {
		data: [],
		pagination: []
	};
	
	$scope.options = {
		filter: {
			status: [
				{
					key: 'all',
					val: 'All'
				},
				{
					key: 1,
					val: 'Active'
				},
				{
					key: 2,
					val: 'Canceled'
				},
				{
					key: 3,
					val: 'Booked'
				},
				{
					key: 4,
					val: 'Expired'
				}
			],
			operators: [
				{
					key: 'all',
					val: 'All'
				}
			]
		},
		modal: {
			status: [
				{
					key: 1,
					val: 'Active'
				},
				{
					key: 2,
					val: 'Canceled'
				}
			]
		}
	};

	api.call('admin/users/operators')
		.success((res) => {
			angular.forEach(res.responseData, function(v){
				$scope.options.filter.operators.push({
					val: v.name,
					key: v.user_id
				});
			})
		});
	

	$scope.filter = {
		date_from: null,
		date_to: null,
		status: $scope.options.filter.status[1],
		operator: $scope.options.filter.status[0]
	};


	pack.add = () => {
		$scope.$broadcast('pack:add');
	};

	pack.edit = (flight_id) => {
		$scope.$broadcast('pack:edit', flight_id);
	};

	$scope.$on('pack:refresh', () => {
		getpack(undefined, {
			status: $scope.filter.status.key
		});
	});

	var getpack = (paginate, filter) => {
		var url = 'admin/pack/';
		if(typeof paginate != 'undefined'){
			url = paginate;
		}
		if(paginate == ''){
			return false;
		}
		api.call(url, filter)
			.success((res) => {
				if(res.status == 1) {
					pack.list.data = res.responseData.data;
					pack.list.pagination = res.responseData.pagination;
				}
			})
	}
	getpack(undefined, {
		status: $scope.filter.status.key
	});

	$scope.paginate = (page) => {
		var url = pack.list.pagination[page];
		getpack(url, {
			status: $scope.filter.status.key
		});
	};


	var filterFirstRun = true;
	$scope.$watchGroup(['filter.date_from', 'filter.date_to', 'filter.status', 'filter.operator'], () => {

		if(filterFirstRun){
			filterFirstRun = false;
			return false;
		}

		var filter = {
			status: $scope.filter.status.key,
			operator: $scope.filter.operator.key
		};

		if($scope.filter.date_from != null){
			filter.date_from = moment($scope.filter.date_from).format('YYYY-MM-DD');
		}

		if($scope.filter.date_to != null){
			filter.date_to = moment($scope.filter.date_to).format('YYYY-MM-DD');
		}

		getpack(undefined, filter);


	});

	$scope.searchLocation = (name) => {

		return searchLocation.search(name).then(function(response){
			return response;
		})
	}


	pack.importCsv = () => {
		alert('select file')
	};

	pack.delete = function(flight_id){


		if(confirm('Delete flight?')){
			api.call('admin/pack/delete', {
				flight_id: flight_id
			})
				.success((r)=>{
					Alert.info('Flight deleted');
					getpack(undefined, {
						status: $scope.filter.status.key
					});
				});
		}

	}

})

.directive('pack', function($modal, api, Upload, Alert, searchAirports){

	return {
		link: function($scope, $elem, args){

			var modal = null;

			var now = moment();

			$scope.form = {
				origin_airport_data: '',
				destination_airport_data: '',
				origin_airport: '',
				destination_airport: '',
				date: {
					year: '2018',
					month: now.format('MM'),
					day: now.format('DD')
				},
				period_from: {
					hours: now.format('HH'),
					minutes: now.format('mm')
				},
				period_to: {
					hours: now.format('HH'),
					minutes: now.format('mm')
				},
				price: 997,
				seats: '',
				aircraft: '',
				aircraft_custom_image: '',
				note: ''
			};
			var empty = $scope.form;

			$scope.errors = {};

			// $scope.min_date = moment(moment(new Date).format('YYYY-MM-DD') + ' 00:00:00');
			//
			// $scope.$watch('form.date', function(){
			// 	if(moment(moment(new Date).format('YYYY-MM-DD')).toString() != moment(moment($scope.form.date).format('YYYY-MM-DD')).toString()){
			// 		$scope.min_time = null;
			// 	}else{
			// 		$scope.min_time = moment(new Date);
			// 	}
			// 	$scope.time_max = moment(moment($scope.form.date).format('YYYY-MM-DD') + ' 23:59:59');
			// })

			$scope.searchAirports = (string) => {

				return searchAirports.search(string);

			};

			$scope.$on('pack:add', function(){
				$scope.errors = {};
				$scope.form = angular.copy(empty);
				$scope.method = 'insert';
				modal = $modal({
					scope: $scope,
					templateUrl: 'pack.modal.html',
					title: 'Add Package',
					show: false
				});

				modal.$promise.then(modal.show);

				modal.$scope.$watch('form.aircraft', function(){
					modal.$scope.form.aircraft_custom_image = null;
					modal.$scope.image_preview_custom = '';
					modal.$scope.image_preview = modal.$scope.form.aircraft.image;
				});

				modal.$scope.customImage = () => {
					modal.$scope.image_preview = null;
					modal.$scope.image_preview_custom = modal.$scope.form.aircraft_custom_image;
				};


				modal.$scope.$watch('form.origin_airport', function(){
					if(modal.$scope.form.origin_airport.length == 4){
						$scope.searchAirports(modal.$scope.form.origin_airport).then(function(response){
							modal.$scope.form.origin_airport_data = response;
						}, function(e){
							console.log(e);
						});
					}
				});

				modal.$scope.$watch('form.destination_airport', function(){
					if(modal.$scope.form.destination_airport.length == 4){
						$scope.searchAirports(modal.$scope.form.destination_airport).then(function(response){
							modal.$scope.form.destination_airport_data = response;
						});
					}
				});



				$scope.$on('modal.hide', () => {
					modal = null;
				});

			});



			$scope.$on('pack:edit', function(e, flight_id){

				$scope.method = 'edit';
				$scope.errors = {};

				api.call('admin/pack/get/' + flight_id)
					.success((res) => {

						if(res.status == 1){

							$scope.form = res.responseData;
							$scope.form.flight_id = flight_id;
							$scope.form.seats = parseInt($scope.form.seats);
							$scope.form.price = parseInt($scope.form.price);
							$scope.form.image_preview_custom = null;
							$scope.form.aircraft = {
								aircraft_id: res.responseData.aircraft_id,
								aircraft_image_id: res.responseData.aircraft_image_id,
								image: res.responseData.aircraft_image,
							};

							$scope.form.origin_airport = res.responseData.origin_airport_iata;
							$scope.form.origin_airport_data = res.responseData.origin_airport_data;
							$scope.form.destination_airport = res.responseData.destination_airport_iata;
							$scope.form.destination_airport_data = res.responseData.destination_airport_data;

							var date = moment($scope.form.date);
							$scope.form.date = {
								year: date.format('YYYY'),
								month: date.format('MM'),
								day: date.format('DD'),
							};

							var period_from = $scope.form.period_from;
							$scope.form.period_from = {
								hours: period_from.split(':')[0],
								minutes: period_from.split(':')[1],
							}

							var period_to = $scope.form.period_to;
							$scope.form.period_to = {
								hours: period_to.split(':')[0],
								minutes: period_to.split(':')[1],
							}

							angular.forEach($scope.options.modal.status, function (v) {
								if(v.key == res.responseData.status){
									$scope.form.status = v
								}
							});

							$scope.image_preview = $scope.form.aircraft_image;

							modal = $modal({
								scope: $scope,
								templateUrl: 'pack.modal.html',
								title: 'Edit flight',
								show: false
							});


							modal.$promise.then(modal.show);


							modal.$scope.$watch('form.aircraft', function(){
								modal.$scope.form.aircraft_custom_image = null;
								modal.$scope.image_preview_custom = '';
								modal.$scope.image_preview = modal.$scope.form.aircraft.image;
							});

							modal.$scope.customImage = () => {
								modal.$scope.image_preview = null;
								modal.$scope.image_preview_custom = modal.$scope.form.aircraft_custom_image;
							};

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

							$scope.$on('modal.hide', () => {
								modal = null;
							});

						}
					});

			})


			$scope.action = (method, form) => {

				form.aircraft_id = form.aircraft.aircraft_id;

				if(!form.aircraft_custom_image){
					form.aircraft_image_id = form.aircraft.aircraft_image_id;
				}else{
					form.aircraft_image_id = 0;
				}

				var stop_submit = false;
				if(typeof form.origin_airport_data != 'object'){
					Alert.info('Please select origin airport from autocomplete results');
					stop_submit = true;
				}

				if(typeof form.destination_airport_data != 'object'){
					Alert.info('Please select pack from autocomplete results');
					stop_submit = true;
				}

				var date = moment(form.date.year + '-' + form.date.month + '-' + form.date.day);
				var period_from = moment(now.format('YYYY-MM-DD').toString() + ' ' + form.period_from.hours + ':' + form.period_from.minutes);
				var period_to  = moment(now.format('YYYY-MM-DD').toString()  + ' ' + form.period_to.hours + ':' + form.period_to.minutes);

				$scope.errors = {};
				if(!date.isValid()){
					Alert.info('Please enter valid date');
					$scope.errors['date'] = 'Please enter valid date';
					stop_submit = true;
				}

				if(!period_from.isValid()){
					Alert.info('Please enter valid time when boarding starts');
					$scope.errors['period_from'] = 'Please enter valid time when boarding starts';
					stop_submit = true;
				}

				if(!period_to.isValid()){
					Alert.info('Please enter valid time when boarding end');
					$scope.errors['period_to'] = 'Please enter valid time when boarding end';
					stop_submit = true;
				}

				if(period_from.isValid() && period_to.isValid()){
					if(parseInt(period_to.format('HH')) < parseInt(period_from.format('HH'))){
						Alert.info('Boarding time is not valid');
						$scope.errors['period_to'] = 'Boarding time is not valid';
						stop_submit = true;
					}
				}

				if(stop_submit){
					return false;
				}

				var data = {};

				switch (method) {
					case 'insert':


						data = {
							'date': date.toString(),
							'period_from': period_from.format('HH:mm'),
							'period_to': period_to.format('HH:mm'),
							'price': form.price,
							'seats': form.seats,
							'aircraft_id': form.aircraft_id,
							'aircraft_image_id': form.aircraft_image_id,
							'origin_airport': form.origin_airport_data,
							'destination_airport': form.destination_airport_data,
							'note': form.note
						};

						api.call('admin/pack/add', data)
							.success((res) => {
								if(res.status == 1){
									if(form.aircraft_custom_image){
										upload(form.aircraft_custom_image, form.aircraft_id, res.responseData.flight_id)
									}
									modal.hide();
									Alert.success('Flight added');
									$scope.$broadcast('pack:refresh');
								}
								if(res.status == 0){
									$scope.errors = res.messages;
								}
							});

						break;

					case 'edit':

						data = {
							'flight_id': form.flight_id,
							'date': date.toString(),
							'period_from': period_from.format('HH:mm'),
							'period_to': period_to.format('HH:mm'),
							'price': form.price,
							'seats': form.seats,
							'status': form.status.key,
							'aircraft_id': form.aircraft_id,
							'aircraft_image_id': form.aircraft_image_id,
							'origin_airport': form.origin_airport_data,
							'destination_airport': form.destination_airport_data
						};

						api.call('admin/pack/edit', data)
							.success((res) => {
								if(res.status == 1){
									if(form.aircraft_custom_image){
										upload(form.aircraft_custom_image, form.aircraft_id, res.responseData.flight_id)
									}
									modal.hide();
									Alert.success('Flight edited');
									$scope.$broadcast('pack:refresh');
								}
								if(res.status == 0){
									$scope.errors = res.messages;
								}
							});



						break;
				}

			};



			var upload = function(file, aircraft_id, flight_id, name){
				Upload.upload({
					url: 'api/admin/upload',
					method: 'POST',
					data: {
						file: file,
						data: {
							type: 'aircraft',
							aircraft_id: aircraft_id,
							flight_id: flight_id,
							name: file.name.substr(0, file.name.lastIndexOf('.'))
						}
					}
				}).then(
					() => {
						modal.hide();
						Alert.success('Aircraft added')
						$scope.$broadcast('pack:refresh');
					},
					() => {
						console.log('Upload error');
					},
					(event) => {
						var progressPercentage = parseInt(100.0 * event.loaded / event.total);
						console.log('progress: ' + progressPercentage + '%');
					}
				);
			}

		}

	}


});



