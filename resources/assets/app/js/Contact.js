export var contact = angular.module('hobo.contact', ['ngFileUpload'])
	
	.controller('ContactController', function(api, $scope, Alert){

		$scope.errors = [];
		$scope.form = {
			name: null,
			phone: null,
			email: null,
			comment: null
		}
		var clean = angular.copy($scope.form);


		$scope.send = (form) => {
			$scope.errors = [];
			api.call('contact/form', form)
				.success((res) => {
					if(res.status == 1){
						Alert.info('Thank you for message. Expected response soon')
						$scope.form = angular.copy(clean);
					}else{
						$scope.errors = res.messages;
					}
				})

		}

	})
