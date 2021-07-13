


angular.module('hoboAdmin.aircraftsImages', [])

	.controller('AircraftsImagesController', function($scope, $routeParams, Alert, getImages, Upload, api){

		var aircraft_id = $routeParams.aircraft_id;

		$scope.images = [];

		$scope.upload = (file) => {
			Upload.upload({
				url: 'api/admin/upload',
				method: 'POST',
				data: {
					file: file,
					data: {
						type: 'aircraft',
						aircraft_id: aircraft_id,
						name: file.name.substr(0, file.name.lastIndexOf('.'))
					}
				}
			}).then(
				() => {
					getImages(aircraft_id)
						.success((res)=>{
							if(res.status == 1){
								$scope.images = res.responseData;
							}
						});
					Alert.success('Image uploaded')
				},
				() => {
					console.log('Upload error');
				},
				(event) => {
					var progressPercentage = parseInt(100.0 * event.loaded / event.total);
					console.log('progress: ' + progressPercentage + '%');
				}
			);
		};


		$scope.setDefault = ($index, image) => {
			angular.forEach($scope.images, (i, k) => {
				i.default = 0;
				if(k == $index){
					i.default = 1;
				}
			});

			api.call('admin/aircrafts/images/default/', {
				image_id: image.image_id,
				aircraft_id: aircraft_id
			});
		};

		$scope.delete = (index, image) => {
			api.call('admin/aircrafts/images/delete/' + image.image_id)
				.success((res) => {
					if(res.status == 1){
						$scope.images.splice(index, 1);
						Alert.success('Image removed')
					}
					if(res.status == 0){
						Alert.info(res.messages.message.error)
					}
				})

		}

		getImages(aircraft_id)
			.success((res)=>{
				if(res.status == 1){
					$scope.images = res.responseData;
				}
			});


	})

	.factory('getImages', function(api){

		return (aircraft_id) => {

			return api.call('admin/aircrafts/images/' + aircraft_id);

		}

	})