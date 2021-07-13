export var hoboSquad = angular.module('hobo.hoboSquad', [])
	.controller('HoboSquadController', function(api, $scope, Alert, $modal){


		$scope.mode = 'display';
		$scope.new_pack_name = '';
		$scope.edit = () => {

			$scope.mode = 'edit';



		}

		$scope.save = (pack_id) => {

			api.call('pack/edit', {
				pack_id: pack_id,
				name: $scope.new_pack_name
			})
				.success((res) => {

					if(res.status == 1){
						$scope.hobo_pack_name = $scope.new_pack_name;
						$scope.new_pack_name = '';
						$scope.mode = 'display';
						Alert.info('HoboSquad name changed');
					}else{
						Alert.info(res.messages.name);
					}

				});

		}

		$scope.remove = (pack_id, user_id, first_name, last_name) => {

			api.call('pack/disconnect', {
				pack_id: pack_id,
				user_id: user_id
			})
				.success((res) => {
					
					if(res.status == 1){
						remove(user_id);
						Alert.info('User ' + first_name + ' ' + last_name + ' removed');

					}else{
						Alert.info(res.messages.message);
					}
					
				})

		}
		function remove(user_id) {
			var elem = document.getElementById('member_' + user_id);
			elem.parentNode.removeChild(elem);
		}

		$scope.invite = () => {
			$modal({
				scope: $scope,
				templateUrl: 'invite-friends.html',
				show: true
			})
		}

		$scope.fb = () => {
			FB.ui({
				method: 'share',
				href: $scope.branch_invite
			});

		}

		$scope.branch_invite = '';
		branch.init('key_live_jio3b3U9iGdiD9I4qOnRrihlEBgKFh6O')
		branch.link({
			data: {
				'$og_title': 'HoboJet',
				'$og_image_url': 'http://hobojet.smc-ol.com/images/logo.png'
			}
		}, function(err, link) {
			if(err) return;
			$scope.branch_invite = link;
			$scope.$apply()
		});

	})