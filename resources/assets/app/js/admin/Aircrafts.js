



angular.module('hoboAdmin.aircrafts', [])
	.controller('AircraftsController', function($scope, api) {
		var aircrafts = this;

		aircrafts.list = {
			data: [],
			pagination: []
		};


		aircrafts.add = () => {
			$scope.$broadcast('aircrafts:add');
		};

		$scope.$on('aircrafts:refresh', () => {
			getAircrafts();
		});

		var getAircrafts = (paginate)=>{
			var url = 'admin/aircrafts/';
			if(typeof paginate != 'undefined'){
				url = paginate;
			}
			if(paginate == ''){
				return false;
			}
			api.call(url)
				.success((res) => {
					if(res.status == 1) {
						aircrafts.list.data = res.responseData.data;
						aircrafts.list.pagination = res.responseData.pagination;
					}
				})
		};
		getAircrafts();

		$scope.paginate = (page) => {
			var url = aircrafts.list.pagination[page];
			getAircrafts(url);
		};


		$scope.edit = (aircraft_id) => {

			$scope.$broadcast('aircrafts:edit', aircraft_id);

		}

		$scope.delete = (aircraft_id) => {
			if (confirm('Are you sure you want to delete this aircraft?')) {
				$scope.$broadcast('aircrafts:delete', aircraft_id);
			}
		}

	})


	.directive('aircrafts', function($modal, Alert, api, Upload){

		return {

			link: function($scope, $elem, attrs){

				var modal = null;

				$scope.form = {
					name: '',
					manufacturer: '',
					seats: ''
				};
				var empty = $scope.form;

				$scope.$on('aircrafts:add', () => {

					$scope.method = 'insert';
					$scope.form = angular.copy(empty);
					modal = $modal({
						scope: $scope,
						templateUrl: 'aircrafts.modal.html',
						title: 'Add aircraft',
						show: false
					});

					modal.$promise.then(modal.show);

					$scope.$on('modal.hide', () => {
						modal = null;
					});


				});

				$scope.$on('aircrafts:edit', (e, aircraft_id) => {

					$scope.method = 'edit';

					api.call('admin/aircrafts/get/' + aircraft_id)
					.success((res) => {

						if(res.status == 1){

							$scope.form = res.responseData;
							$scope.form.seats = parseInt($scope.form.seats);
							$scope.form.aircraft_id = aircraft_id;

							modal = $modal({
								scope: $scope,
								templateUrl: 'aircrafts.modal.html',
								title: 'Edit aircraft',
								show: false
							});

							modal.$promise.then(modal.show);

							$scope.$on('modal.hide', () => {
								modal = null;
							});

						}
					});

				});

				$scope.$on('aircrafts:delete', (e, aircraft_id) => {
					api.call('admin/aircrafts/delete/' + aircraft_id, undefined, 'DELETE')
						.success(res => {
							$scope.$broadcast('aircrafts:refresh');
							Alert.success(res.responseData.message);
						})
						.error(res => Alert.error(res.responseData.message));
				});

				$scope.action = (method, form, file) => {

					switch (method) {
						case 'insert':
							api.call('admin/aircrafts/add', form)
								.success((res) => {
									if(res.status == 1){
										if(file){
											upload(file, res.responseData.aircraft_id, form.name);
											modal.$scope.form = form;
										}else{
											Alert.success('Aircraft added (without image)');
											modal.hide();
											$scope.$broadcast('aircrafts:refresh');
										}
									}
								});
							break;

						case 'edit':

							api.call('admin/aircrafts/edit', form)
								.success((res) => {
									if(res.status == 1){
										Alert.success('Aircraft edited')
										modal.hide();
										$scope.$broadcast('aircrafts:refresh');
									}
								});

							break;

					}

				}


				var upload = function(file, aircraft_id, name){
					Upload.upload({
						url: 'api/admin/upload',
						method: 'POST',
						data: {
							file: file,
							data: {
								type: 'aircraft',
								aircraft_id: aircraft_id,
								name: name,
								new: 1
							}
						}
					}).then(
						() => {
							modal.hide();
							Alert.success('Aircraft added')
							$scope.$broadcast('aircrafts:refresh');
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
	})


	.directive('listAircrafts', function(api, $q){


		return {



			link: function($scope){

				$scope.aircrafts = [];
				api.call('admin/aircrafts/list').success((res) => {
					if(res.status == 1){
						$scope.aircrafts = res.responseData;
						if(typeof $scope.form.aircraft !== 'undefined'){
							angular.forEach($scope.aircrafts, (v) => {

								if($scope.form.aircraft.aircraft_id == v.aircraft_id){
									$scope.form.aircraft = v;
								}

							});
						}

					}
				});



			}

		}

	});