


angular.module('hoboAdmin.packages', [])
.controller('PackagesController', function($scope, api, $timeout, $modal, Alert, $route) {
	var packages = this;
	packages.list = {
		data: [],
		pagination: []
	};

	$scope.filters = {
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
				val: 'Not confirmed'
			},
			{
				key: 0,
				val: 'Suspended'
			}
		]
	};

	$scope.plan_types = {
		plan: [
			{
				key: 1,
				val: 'Trial'
			},
			{
				key: 2,
				val: 'Paid'
			}
		]
	};

 
	$scope.filter = {
		status: $scope.filters.status[1]
	};

	packages.edit = (user_id) => {
		$scope.method = 'edit';
		$scope.$broadcast('packages:edit', user_id);
	};

	$scope.$on('packages:refresh', () => {
		getpackages(undefined, {
			status: $scope.filters.status[1].key
		});
	});

	var getpackages = (paginate, filter) => {
		var url = 'packages/all';
		if(typeof paginate != 'undefined'){
			url = paginate;
		}
		if(paginate == ''){
			return false;
		}
		api.call(url, filter)
			.success((res) => {
				if(res.status == 1) {
					console.log(res.responseData.billing_packages);
					packages.list.data = res.responseData.billing_packages;
					packages.list.pagination = res.responseData.pagination;
				}
			})
	}
	getpackages(undefined, {
		status: $scope.filters.status[1].key
	});

	$scope.paginate = (page) => {
		var url = packages.list.pagination[page];
		getpackages(url);
	};


	var filterFirstRun = true;
	$scope.$watchGroup(['filter.status'], () => {

		if(filterFirstRun){
			filterFirstRun = false;
			return false;
		}

		var filter = {
			status: $scope.filter.status.key
		};

		getpackages(undefined, filter);

	});


	var modal;
	$scope.notificationModal = (user_id) => {

		$scope.user_id = user_id;

		modal = $modal({
			scope: $scope,
			templateUrl: 'notifications.modal.send.html',
			title: 'Send notification',
			show: false
		});

		modal.$promise.then(modal.show);

	};

	
	$scope.info = (package_id) => {
		api.call('packages/edit/' + package_id)
			.success((res) => {
				$scope.user_info = res.responseData;
				modal = $modal({
					scope: $scope,
					templateUrl: 'packages.info.modal.html',
					title: 'Package details',
					show: false
				});
				modal.$promise.then(modal.show);
			})
	}

	var edit_modal = $modal({
		scope: $scope,
		templateUrl: 'packages.info.edit.modal.html',
		title: 'Package details',
		show: false
	});
	
	$scope.edit = (package_id) => {
		$scope.method = 'edit';
		api.call('packages/edit/' + package_id)
			.success((res) => {
				$scope.package_info = res.responseData.package[0];
				console.log($scope.package_info);
				edit_modal.$scope.form = {
					description: $scope.package_info.description,
					price: parseFloat($scope.package_info.price),
					package_name: $scope.package_info.name,
					package_id: $scope.package_info.package_id,
					billing_interval: parseInt($scope.package_info.billing_interval),
					package_discount: parseFloat($scope.package_info.discount),
					stripe_price_id: $scope.package_info.stripe_price_id,
				};
				angular.forEach($scope.options.status, function(v){
					if(v.key == res.responseData.status){
						edit_modal.$scope.form.status = v;
					}
				})
				angular.forEach($scope.options.roles, function(v){
					if(v.key == res.responseData.role){
						edit_modal.$scope.form.role = v;
					}
				})


				setTimeout(() => {
					edit_modal.$promise.then(edit_modal.show);
				}, 100);
			})

	}


	$scope.options = {
		status: [{
			key: 1,
			val: 'Active'
		},{
			key: 2,
			val: 'Not confirmed'
		},{
			key: 0,
			val: 'Suspended'
		}],
		roles: [{
			key: 'admin',
			val: 'Admin'
		},{
			key: 'operator',
			val: 'Operator'
		},{
			key: 'user',
			val: 'User'
		}]
	};


	$scope.save = (form) => {
		api.call('packages/edit', {
			user_id: form.user_id,
			role: form.role.key,
			status: form.status.key,
			first_name: form.firstname,
			last_name: form.lastname,
			email: form.email,
			password: form.password,
		})
			.success((res)=>{
				if (res.status == 1) {
					edit_modal.$promise.then(edit_modal.hide);
					Alert.info('User edited');
					getpackages(undefined,  {
						status: $scope.filter.status.key
				});
				}
				if (res.status == 0) {
					$scope.errors = res.messages;
				}

			})


	}

	$scope.add = () => {
		$scope.$broadcast('packages:add');
	}
	
	var quill = "";
	
	$scope.initForm = () => {
        $timeout(function() {
			
			quill = new Quill('#desc-editor', {
				//modules: { toolbar: '#toolbar' },
				theme: 'snow'
			});
			
			if($scope.method == 'insert') {
				quill.container.firstChild.innerHTML = '<p>Please enter description.</p>';
			} else {
				quill.container.firstChild.innerHTML = $scope.package_info.description;
			}
        });
	};

	$scope.$on('packages:add', function() {
		$scope.errors = {};
		$scope.form = angular.copy($scope.form);
		$scope.method = 'insert';
		modal = $modal({
			scope: $scope,
			templateUrl: 'create-packages.modal.html',
			title: 'Add User',
			show: false
		});

		modal.$promise.then(modal.show);
	});

	$scope.action = (method, form) => {
		if (method == "insert") {
			var data = {
				'package_name' : form.package_name,
				'status' : form.status,
				'description' : quill.container.firstChild.innerHTML,
				'price' : form.price,
				'package_discount' : form.discount,
				'stripe_price_id' : form.stripe_price_id,
			};

			api.call('packages/create', data)
				.success((res) => {
					if (res.status == 1) {
						modal.hide();
						Alert.success('The package has been added');
						$route.reload();
					}
					if (res.status == 0) {
						$scope.errors = res.messages;
					}
				});
		}
		else if (method == "edit") {
			
			var data = {
				'package_id': form.package_id,
				'package_name' : form.package_name,
				'status' : form.status,
				'description' : quill.container.firstChild.innerHTML,
				'price' : form.price,
				'billing_interval': form.billing_interval,
				'package_discount' : form.package_discount,
				'stripe_price_id' : form.stripe_price_id,
			};
			
			api.call('packages/updatePackage', data)
				.success((res) => {
					if (res.status == 1) {
						edit_modal.hide();
						Alert.success('The package has been updated.');
						$route.reload();
					}
					if (res.status == 0) {
						$scope.errors = res.messages;
					}
				});
		}
	};


	$scope.delete = (user_id) => {
		api.call("packages/delete/" + user_id)
			.success((res) => {
				if (res.status == 1) {
					Alert.success('User deleted');
					$route.reload();
				}
				if (res.status == 0) {
					$scope.errors = res.messages;
				}
			});
	};
});
