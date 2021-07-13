
angular.module('hoboAdmin.common', [])

	.factory('searchAirports', function(api, $q, Alert){


		return {
			search: function(query){


				var defer = $q.defer();

				if(typeof query == 'object'){
					defer.resolve([query]);
					return defer.promise;
				}

				if(query.length != 4) {
					defer.reject('');
				}else{
					api.call('admin/airports/search', {string: query})
						.success((res) => {
							if(res.status == 1){
								defer.resolve(res.responseData);
							}
							if(res.status == 0){
								Alert.info(res.messages.message);
							}
						});

				}

				return defer.promise;

			},
			cancel: function(){
				defer.resolve('New search');
			}
		}


	})

	.factory('searchLocation', function(api, $q){


		return {
			search: function(query){

				var defer = $q.defer();

				if(typeof query == 'object'){
					defer.resolve([query]);
					return defer.promise;
				}

				if(query.length < 3) {
					defer.reject('');
				}else{
					api.call('cities/search', {search: query})
						.success((res) => {
							defer.resolve(res.responseData);
						});

				}

				return defer.promise;

			},
			cancel: function(){
				defer.resolve('New search');
			}
		}


	})

