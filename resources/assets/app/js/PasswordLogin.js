// export var membership = angular.module('hobo.membership', [])
// 	.controller('PasswordLoginController', function(api, $scope, $rootScope, $modal, Alert){
//
// 	    $scope.form = {
// 			password: null,
// 		};
//
// 		$scope.errors = {};
//
// 		$scope.sendPassword = () => {
// 			api.call('auth/password-login', $scope.form)
// 				.success((res) => {
// 				console.log(res);
// 					if(res.status == 1){
// 						if(res.status == 1) {
//                             window.location.reload();
//                         }
// 					}else{
// 						$scope.errors = res.messages;
// 					}
// 				});
// 		}
//     });