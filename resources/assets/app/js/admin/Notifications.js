


angular.module('hoboAdmin.notifications', [])
	.controller('NotificationsController', function($scope, $rootScope, api, $modal, Alert) {
		var notifications = this;
		notifications.list = {
			data: [],
			pagination: []
		};

		var getNotifications = (paginate) => {
			var url = 'admin/notifications/get';
			if(typeof paginate != 'undefined'){
				url = paginate;
			}
			if(paginate == ''){
				return false;
			}
			api.call(url)
				.success((res) => {
					if(res.status == 1) {
						notifications.list.data = res.responseData.data;
						notifications.list.pagination = res.responseData.pagination;
					}
				})
		};
		getNotifications(undefined);

		$scope.paginate = (page) => {
			var url = notifications.list.pagination[page];
			getNotifications(url);
		};


		var modal;
		$scope.notificationModal = () => {

			modal = $modal({
				scope: $scope,
				templateUrl: 'notifications.modal.send.html',
				title: 'Send notification',
				show: false
			});

			modal.$promise.then(modal.show);



		};

		$scope.notificationSend = (message) => {

			api.call('admin/notifications/add', {
					message: message,
					user_id: 0
				})
				.success(() => {
					Alert.info('Message added to queue')
					modal.$promise.then(modal.hide);
					modal = null;
				})


		};


	});


