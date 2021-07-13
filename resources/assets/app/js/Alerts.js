

export var alerts = angular.module('hobo.ui.alerts', [])

	.factory('Alert', function($alert){

		var _alert = function(content, title, type){

			if(typeof title == 'undefined'){
				title = '';
			}

			$alert({
				title: title,
				content: content,
				placement: 'top-right',
				container: 'body',
				type: type,
				duration: 5,
				animation: 'am-fade-and-slide-top'
			});
		};

		return {

			success: function(content, title){
				return _alert(content, title, 'success');
			},
			info: function(content, title){
				return _alert(content, title, 'info');
			},
			error: function(content, title){
				return _alert(content, title, 'error');
			},
			warning:function(content, title){
				return _alert(content, title, 'warning');
			}
		}

	});
