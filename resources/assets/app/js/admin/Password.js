var password = angular.module('hoboAdmin.password', [])

password.controller('PasswordController', function(api, $scope){

    $scope.form = {
			old_password: null,
			new_password: null,
		};

		$scope.errors = {};

    $scope.changePassword = () => {
        console.log($scope.form);
        api.call('admin/password/change-password', $scope.form)
            .success((res) => {
                if (res.status == 1) {
                    $scope.form.old_password = '';
                    $scope.form.new_password = '';
                    $scope.errors = res.responseData;
                } else {
                    $scope.errors = res.messages;
                }
            });
    }
});