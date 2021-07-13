//Api.js

export var api = angular.module('hobo.api', [])

	.factory('api', function($http){

		return {

			call: function(path, data, method){
				
				var call = this._call(path, data, method);
				call.success(function(res){
					console.groupCollapsed('API call:', path);
					if(typeof data !== 'undefined'){
						console.log('request', data);
					}
					console.log('response', res);
					console.groupEnd();

				});
				call.error(function(res){
					console.groupCollapsed('ERROR API call:', path);
					console.log('response', res);
					console.groupEnd();
				});

				return call;

			},

			_call: function(path, data, method){

				var what = method || 'GET';
				if(typeof data !== 'undefined'){
					what = 'POST';
				}

				switch (what)
				{
					case 'GET':

						return $http({
							method: 'GET',
							url: 'api/' + path
						});

						break;

					case 'POST':

						return $http({
							method: 'POST',
							url: 'api/' + path,
							data: data
						});

						break;
					case 'DELETE':
						return $http({
							method: 'DELETE',
							url: 'api/' + path
						});
				}



			}
		}


	})

