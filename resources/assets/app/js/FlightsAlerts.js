export var flightsAlerts = angular.module('hobo.flightAlerts', [])
	.controller('AlertsController', function(api, $scope, $modal, Alert, $compile){

		var modal = null;
		$scope.create = () => {
			
			modal = $modal({
				scope: $scope,
				templateUrl: 'add-alert.html',
				show: false
			});

			modal.$promise.then(modal.show);

		}


		$scope.delete = (alert_id) => {

			if(confirm('Delete alert?')){

				api.call('alerts/del', {
					alert_id: alert_id
				})
					.success((res) => {
						if(res.status){
							remove(alert_id);
							Alert.info('Alert removed');
						}
					});

			}

		}


		$scope.add = (form) => {

			modal.$scope.error = null;

			if(typeof form == 'undefined'){
				modal.$scope.error = 'Please enter origin and destination';
				return;
			}

			if(typeof form.origin_location == 'undefined'){
				modal.$scope.error = 'Missing origin';
				return;
			}
			if(typeof form.destination_location == 'undefined'){
				modal.$scope.error = 'Missing destination';
				return;
			}


			api.call('alerts/add', {
				origin: form.origin_location.formatted_address,
				origin_longitude: form.origin_location.geometry.lng,
				origin_latitude: form.origin_location.geometry.lat,
				destination: form.destination_location.formatted_address,
				destination_longitude: form.destination_location.geometry.lng,
				destination_latitude: form.destination_location.geometry.lat
			})
				.success((res) => {
					if(res.status == 1){
						Alert.info(res.responseData.message);

						var alerts = angular.element(document.getElementById('alerts_list'));

						var html =
							'<div class="alert-col" id="alert_'+res.responseData.alert_id+'">'+
								'<button class="remove-alert-btn" ng-click="delete('+res.responseData.alert_id+')"><i class="ti-close"></i></button>'+
								'<article class="alert-box">'+
									'<p class="alert-orig">'+form.origin_location.formatted_address+'</p>'+
									'<p class="alert-dest">'+form.destination_location.formatted_address+'</p>'+
								'</article>'+
							'</div>'
						var c = $compile(html)($scope);
						alerts.append(c);
						modal.$promise.then(modal.hide);
					}
				});

		}


		function remove(alert_id) {
			var elem = document.getElementById('alert_' + alert_id);
			elem.parentNode.removeChild(elem);
		}
		

	});
