export var subscribe = angular.module('hobo.subscribe', [])
	.controller('SubscribeController', function($scope, api){
		

		$scope.subscribe = (user_token, package_id) => {


			api.call('paypal/subscribe/new', {
				user_token: user_token,
				package_id: package_id
			})
				.success((res)=>{
					if(res.status == 1){
						var pending_id = res.responseData.pending_id;

						if(typeof res.responseData.pending_id == 'undefined'){
							window.location.href = 'paypal/success';
							return;
						}

						var form = angular.element(document.getElementById('paypalform'));

						form.append(
							'<input type="hidden" name="custom" value="s:'+pending_id+'">'
						);

						form.submit();
					}
				});
			
		}
		

	});