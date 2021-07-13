


angular.module('hoboAdmin.users', [])
	.controller('UsersController', function($scope, api, $modal, Alert, $route) {
		var users = this;
		users.list = {
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

		$scope.filter = {
			status: $scope.filters.status[1]
		};

		users.edit = (user_id) => {
			$scope.$broadcast('users:edit', user_id);
		};

		$scope.$on('users:refresh', () => {
			getUsers(undefined, {
				status: $scope.filters.status[1].key
			});
		});

		var getUsers = (paginate, filter) => {
			var url = 'admin/users/';
			if(typeof paginate != 'undefined'){
				url = paginate;
			}
			if(paginate == ''){
				return false;
			}
			api.call(url, filter)
				.success((res) => {
					if(res.status == 1) {
						users.list.data = res.responseData.data;
						users.list.pagination = res.responseData.pagination;
					}
				})
		}
		getUsers(undefined, {
			status: $scope.filters.status[1].key
		});

		$scope.paginate = (page) => {
			var url = users.list.pagination[page];
			getUsers(url);
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

			getUsers(undefined, filter);

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

		$scope.notificationSend = (message, user_id) => {

			api.call('admin/notifications/add', {
					message: message,
					user_id: user_id,
				})
				.success(() => {
					Alert.info('Message added to queue');
					modal.$promise.then(modal.hide);
					modal = null;
				})


		};
		
		
		$scope.info = (user_id) => {
			
			api.call('admin/users/user/' + user_id)
				.success((res) => {
					api.call('packages/all', {}).success((pres)=>{
						$scope.package_info = res.responseData.package;
						$scope.user_info = res.responseData.user;

						console.log('package_info', $scope.package_info);
						modal = $modal({
							scope: $scope,
							templateUrl: 'users.info.modal.html',
							title: 'User details',
							show: false
						});

						modal.$promise.then(modal.show);
					});
				})
			
		}

		var edit_modal = $modal({
			scope: $scope,
			templateUrl: 'users.info.edit.modal.html',
			title: 'User details',
			show: false
		});
		
		$scope.options = {
			packages: [
				{
					key: 1,
					val: 'Hobo Tier'
				},
				{
					key: 2,
					val: 'Founder Tier'
				}
			],
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


		$scope.edit = (user_id) => {

			api.call('admin/users/user/' + user_id)
				.success((res) => {
					api.call('packages/all', {}).success((pres)=>{
						$scope.user_info = res.responseData.user;
						$scope.package_info = res.responseData.package;
						
						var billing_packages = pres.responseData['billing_packages'];
						console.log($scope.package_info);

						var packages = [];
						for(var i = 0; i < billing_packages.length; i++) {
							packages.push({ key: billing_packages[i].package_id, val: billing_packages[i].name });
						}

						console.log('packages:', packages);
						
						edit_modal.$scope.options = {
							...edit_modal.$scope.options,
							packages: packages
						};

						edit_modal.$scope.form = {
							package: {},
							status: {},
							role: {},
							email: $scope.user_info.email,
							firstname: $scope.user_info.first_name,
							lastname: $scope.user_info.last_name,
							password: '', 
							user_id: user_id,
						};

						angular.forEach($scope.options.status, function(v){
							if(v.key == res.responseData.user.status){
								edit_modal.$scope.form.status = v;
							}
						})
						angular.forEach($scope.options.roles, function(v){
							if(v.key == res.responseData.user.role){
								edit_modal.$scope.form.role = v;
							}
						})
						angular.forEach($scope.options.packages, function(v){
							if(v.key == res.responseData.package.package_id){
								edit_modal.$scope.form.package = v;
							}
						})
						console.log('scope', edit_modal.$scope);
						edit_modal.$scope.form.package = packages[0];
						setTimeout(() => {
							edit_modal.$promise.then(edit_modal.show);
						}, 100);
					});
				})

		}



		$scope.save = (form) => {
			api.call('admin/users/edit', {
				user_id: form.user_id,
				role: form.role.key,
				status: form.status.key,
				package: form.package.key,
				first_name: form.firstname,
				last_name: form.lastname,
				email: form.email,
				password: form.password,
			})
				.success((res)=>{
					if (res.status == 1) {
						edit_modal.$promise.then(edit_modal.hide);
						Alert.info('User edited');
						getUsers(undefined,  {
							status: $scope.filter.status.key
					});
					}
					if (res.status == 0) {
						$scope.errors = res.messages;
					}

				})


		}

		$scope.add = () => {
			$scope.$broadcast('users:add');
		}

		$scope.$on('users:add', function() {
            $scope.errors = {};
            $scope.form = angular.copy($scope.form);
            $scope.method = 'insert';
            modal = $modal({
                scope: $scope,
                templateUrl: 'create-users.modal.html',
                title: 'Add User',
                show: false
            });

            modal.$promise.then(modal.show);
        });

		$scope.action = (method, form) => {
            if (method == "insert") {
                var data = {
                	'email' : form.email,
                	'first_name' : form.first_name,
					'last_name' : form.last_name,
					'password' : form.password
                };

                api.call('admin/users/create', data)
                    .success((res) => {
                        if (res.status == 1) {
                            modal.hide();
                            Alert.success('User added');
                        }
                        if (res.status == 0) {
                            $scope.errors = res.messages;
                        }
                    });
            }
        };


		$scope.delete = (user_id) => {
			api.call("admin/users/delete/" + user_id)
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
