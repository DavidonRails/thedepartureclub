
var settings = angular.module('hoboAdmin.settings');

settings.controller('SettingsPromoController', function($scope, api, Upload, Alert){

		var promo = this;

		promo.list = {
			data: [],
			pagination: []
		};


		var getImages = () => {
			api.call('admin/promo/get')
				.success((res) => {
					promo.list.data = res.responseData;
				})
		}
		getImages();


		$scope.move = (direction, image_id) => {


			api.call('admin/promo/move', {
				direction: direction,
				image_id: image_id
			})
				.success(()=>{
					getImages();
				})

		}


		$scope.upload = () => {

			Upload.upload({
				url: 'api/admin/upload',
				method: 'POST',
				data: {
					file: $scope.promo_image,
					data: {
						type: 'promo'
					}
				}
			}).then(
				(res) => {
					if(res.data.status == 0){
						Alert.info(res.data.messages.message);
						return;
					}
					Alert.success('Image added');
					getImages();
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