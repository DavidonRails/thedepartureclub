//auth

export var auth = angular.module('hobo.auth', [])

	.run(function(){

		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		window.fbAsyncInit = function() {
			FB.init({
				appId: fbid,
				cookie: true,
				version    : 'v2.2'
			});
		}

	})

	.controller('LoginController', function(api, $scope, facebook, Alert){
		$scope.errors = {};

		$scope.login = function(){
			$scope.errors = {};
			api.call('auth/login', {
				email: $scope.form.login.email.$viewValue,
				password: $scope.form.login.password.$viewValue,
				type: 'web',
				remember: $scope.form.login.remember.$viewValue
			})
				.success(function(response){
					console.log("Login Response : ", response.status);

					
					if(response.status == 1){
						window.location.reload();
						return;
					}
					
					if(response.status == '0'){
						$scope.errors = response.messages;
						if(typeof response.messages.notification != 'undefined'){
							Alert.info(response.messages.notification);
						}
						return;
					}
					
					if(response.responseData.user.active == '2') {
						window.location.href = '/membership';
						return;
					}
				});

		}

		$scope.facebook = function(){

			facebook.login().then(function(response){
				if(response.status == 1){
					window.location.reload();
				}
				if(response.status == 0){
					$scope.errors = response.messages;

					if(typeof response.messages.notification != 'undefined'){
						Alert.info(response.messages.notification);
					}
				}
			});

		}
	})

	.controller('RegisterController', function(api, $scope, facebook, Alert){
		$scope.errors = {};

		$scope.register = function(){
			$scope.errors = {};

			api.call('auth/register', {
				first_name: $scope.form.register.first_name.$viewValue,
				last_name: $scope.form.register.last_name.$viewValue,
				email: $scope.form.register.email.$viewValue,
				password: $scope.form.register.password.$viewValue,
				type: 'web'
				// tier: $scope.form.register.radios.$viewValue
			})
				.success(function(response){
					if(response.status == 1){
						window.location.reload();
					}
					if(response.status == 0){
						$scope.errors = response.messages;

						if(typeof response.messages.notification != 'undefined'){
							Alert.info(response.messages.notification);
						}

					}
				});
		};

		$scope.facebook = function(){

			facebook.register().then(function(response){
				if(response.status == 1){
					window.location.reload();
				}
				if(response.status == 0){
					$scope.errors = response.messages;

					if(typeof response.messages.notification != 'undefined'){
						Alert.info(response.messages.notification);
					}
				}
			});

		}

	})
	
	.controller('ResetController', function(api, $scope, Alert){
		
		$scope.errors = {};
		
		$scope.reset = function(){
			$scope.errors = {};
			var email = $scope.form.reset.email.$viewValue;

			if(typeof email == 'undefined' || !email.length){
				$scope.errors = {
					email: 'Please enter email'
				}
				return;
			}

			api.call('auth/password/reset',
				{
					email: email
				})
				.success((res) => {
					if(res.status == 0){
						$scope.errors = res.messages;
						Alert.info("We don't have user with this email")
					}else{


							Alert.info('Check email');


					}

				})
			
		}
		
	})

	.factory('facebook', function(api, $q){

		var status = function() {

			var defer = $q.defer();

			FB.getLoginStatus(function(response) {

				if(typeof response.status != 'undefined'){
					switch (response.status) {
						case 'not_authorized':
							defer.resolve(0);
							break;
						case 'connected':
							defer.resolve(response);
							break;
						case 'unknown':
							alert('You are not logged on facebook');
							break;
					}
				}

			});

			return defer.promise;

		};

		return {

			register: function(){

				var defer = $q.defer();

				status().then(function(res){

					if(res == 0){

						FB.login(function(response){
							if(response.authResponse){


								api.call('auth/fb/register', {
									token: response.authResponse.accessToken,
									tier: 1,
									type: 'web'
								}).success(function(response){

									defer.resolve(response);
								});

							}
						}, {scope: 'email'});

					}else{

						window.location.href = 'auth/login';


					}
				});

				return defer.promise;


			},

			login: function(){

				var defer = $q.defer();


				status().then(function(res){

					if(res != 0){

						api.call('auth/fb/login', {
							token: res.authResponse.accessToken,
							type: 'web'
						}).success(function(response){

							defer.resolve(response);

						});

					}else{
						window.location.href = 'auth/register';

					}
				});

				return defer.promise;


			}

		}

	});


