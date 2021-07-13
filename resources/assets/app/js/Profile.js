
export var profile = angular.module('hobo.profile', ['ngFileUpload'])

	.controller('ProfileController', function($scope, api, Upload, Alert){

		var profile = this;

		$scope.errors = [];
		$scope.avatar = null;

		profile.updateProfile = () => {
			$scope.errors = [];

			var data = {
				email: $scope.form.profile.email.$viewValue,
				first_name: $scope.form.profile.first_name.$viewValue,
				last_name: $scope.form.profile.last_name.$viewValue,
				company: $scope.form.profile.company.$viewValue
			};

			api.call('profile/update', data)
				.success((res) => {
					if(res.status == 1){

						Alert.info('Profile updated');

						setTimeout(function(){
							window.location.reload();
						}, 600)
					}else{
						$scope.errors = res.messages;
					}
				});

			if($scope.avatar){
				upload($scope.avatar);
			}


		};

		profile.updatePassword = () => {

			$scope.errors = [];
			var data = {
				old_password: $scope.form.password.old_password.$viewValue,
				new_password: $scope.form.password.new_password.$viewValue,
				new_password_repeat: $scope.form.password.new_password_repeat.$viewValue
			}


			api.call('settings/change/password', data)
				.success((res) => {
					if(res.status == 1){

						Alert.info('Password updated');

						$scope.form.password = angular.copy({
							old_password: '',
							new_password: '',
							new_password_repeat: ''
						});

					}else{
						$scope.errors = res.messages;
					}
				});

		};



		profile.closeAccount = () => {


			api.call('settings/close', {})
				.success((res) => {
					if(res.status == 1){
						window.location = '/';
					}
				})
			

		}

		var upload = function(file){
			Upload.upload({
				url: 'upload/',
				method: 'POST',
				data: {
					file: file,
					type: 'avatar'
				}
			}).then(
				() => {
					console.log('upload done');
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


	});