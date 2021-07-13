


angular.module('hoboAdmin.events', [])
.controller('EventsController', function($scope, api, $modal, $timeout, Alert, Upload, $route) {
	var events = this;

	var now = moment();

	events.list = {
		data: [],
		pagination: []
	};

	$scope.form = {
		status: {},
		role: {},
		event_name : '',
		// period_from['hours'] : $scope.event_info.period_from.hours,
		// period_from['minutes'] : $scope.event_info.period_from.minutes,
		// period_to['hours'] : $scope.event_info.period_to.hours,
		// period_to['minutes'] : $scope.event_info.period_to.minutes,
		location : '',
		description : '',
		event_image : '',
		seats: '',
		event_id: '',
	}

	$scope.filters = {
		status: [
			{
				key: 'all',
				val: 'All'
			},
			{
				key: 1,
				val: 'Active'
			},
			{
				key: 2,
				val: 'Not confirmed'
			},
			{
				key: 0,
				val: 'Suspended'
			}
		]
	};

	$scope.filter = {
		status: $scope.filters.status[1]
	};

	events.edit = (event_id) => {
		$scope.$broadcast('events:edit', event_id);
	};

	$scope.$on('events:refresh', () => {
		
		getevents(undefined, {
			status: $scope.filters.status[1].key
		});
	});

	var getevents = (paginate, filter) => {
		var url = 'events/getAll';
		if(typeof paginate != 'undefined'){
			url = paginate;
		}
		if(paginate == ''){
			return false;
		}
		api.call(url, filter)
			.success((res) => {
				if(res.status == 1) {
					var results = res.responseData; 
					results.forEach((item) => { 
						// console.log(item);
					}); 
					
					events.list.data = results;
					events.list.pagination = results;
				}
			})
	};
	
	getevents(undefined, {
		status: $scope.filters.status[1].key
	});

	$scope.paginate = (page) => {
		
		var url = events.list.pagination[page];
		getevents(url);
	};


	var filterFirstRun = true;
	$scope.$watchGroup(['filter.status'], () => {
		
		if(filterFirstRun){
			filterFirstRun = false;
			return false;
		}

		var filter = {
			status: $scope.filter.status.key
		};

		getevents(undefined, filter);

	});


	var modal;
	$scope.notificationModal = (user_id) => {

		$scope.user_id = user_id;

		modal = $modal({
			scope: $scope,
			templateUrl: 'notifications.modal.send.html',
			title: 'Send notification',
			show: false
		});

		modal.$promise.then(modal.show);



	};

	$scope.notificationSend = (message, user_id) => {

		api.call('notifications/add', {
				message: message,
				user_id: user_id,
			})
			.success(() => {
				Alert.info('Message added to queue');
				modal.$promise.then(modal.hide);
				modal = null;
			})


	};
	
	
	$scope.info = (event_id) => {
		$scope.method = 'edit';
		api.call('events/' + event_id)
			.success((res) => {
				$scope.user_info = res.responseData;
				modal = $modal({
					scope: $scope,
					templateUrl: 'events.info.edit.modal.html',
					title: 'Event details',
					show: false
				});

				modal.$promise.then(modal.show);
			})
		
	}
	
	$scope.edit = (event_id) => {
		modal = $modal({
			scope: $scope,
			templateUrl: 'events.info.edit.modal.html',
			title: 'Event details',
			show: false
		});

		$scope.method = 'edit';

		api.call('events/' + event_id)
			.success((res) => {
				$scope.event_info = res.responseData;
				
				/*
				{
					created_at: null
					description: "We are finally moved in."
					event_at: "2020-05-31 21:21:09"
					event_ends_at: null
					event_image: "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRR9gjbb1IvWEjU1BtGTSkWPWLFWbpWCpZUZ6Djme17EQFmCVHY&usqp=CAU"
					event_image_id: null
					id: 2
					location: "Test2"
					name: "Test 3"
					seats: 32
					updated_at: null
				}
				*/
				var event_date = {
					year: "",
					month: "",
					day: "",
					hours: "",
					minutes: "",
				};
				
				var event_end_date = {
					year: "",
					month: "",
					day: "",
					hours: "",
					minutes: "",
				};
				
				var period_from = {
					hours : "",
					minutes : "",
				};

				var period_to = {
					hours : "",
					minutes : "",
				};
				
				if($scope.event_info.event_at) {
					var date = moment($scope.event_info.event_at);
					
					event_date = {
						year: date.format('YYYY'),
						month: date.format('MM'),
						day: date.format('DD'),
						hours: date.format('HH'),
						minutes: date.format('mm'),
					};
					
					period_from = {
						hours: event_date.hours,
						minutes: event_date.minutes,
					};
				}
				
				if($scope.event_info.event_ends_at) {
					var date = moment($scope.event_info.event_ends_at);
					
					event_end_date = {
						year: date.format('YYYY'),
						month: date.format('MM'),
						day: date.format('DD'),
						hours: date.format('HH'),
						minutes: date.format('mm'),
					};
					
					period_to = {
						hours: event_end_date.hours,
						minutes: event_end_date.minutes,
					};
				}
								
				modal.$scope.form = {
					'event_id' : event_id,
					
					'status' : {},
					'role' : {},
					
					'event_name' : $scope.event_info.name,
					'location' : $scope.event_info.location,
					
					'date' : event_date,
					'end_date' : event_end_date,
					
					'event_at' : $scope.event_info.event_at,
					'period_from' : period_from,
					
					'event_to' : $scope.event_info.event_ends_at,
					'period_to' : period_to,
					
					'description' : $scope.event_info.description,
					'event_image' : $scope.event_info.event_image,
					'seats' : $scope.event_info.seats,
				};
				
				modal.$scope.image_preview = $scope.event_info.event_image;
				modal.$scope.image_preview_custom = null;
				
				angular.forEach($scope.options.status, function(v){
					if(v.key == res.responseData.status){
						modal.$scope.form.status = v;
					}
				});
				angular.forEach($scope.options.roles, function(v){
					if(v.key == res.responseData.role){
						modal.$scope.form.role = v;
					}
				});
				
				setTimeout(() => {
					modal.$promise.then(modal.show);
				}, 100);
				
				modal.$scope.customImage = () => {
					console.log('yoddle 1', modal.$scope.image_preview_custom);
					console.log('yoddle 2', modal.$scope.form.event_image);
					
					modal.$scope.image_preview = null;
					modal.$scope.image_preview_custom = modal.$scope.form.event_image;
				};
				
				console.log("Event Detail :", modal.$scope.form);

			})

	}


	$scope.options = {
		status: [{
			key: 1,
			val: 'Active'
		},{
			key: 2,
			val: 'Not confirmed'
		},{
			key: 0,
			val: 'Suspended'
		}],
		roles: [{
			key: 'admin',
			val: 'Admin'
		},{
			key: 'operator',
			val: 'Operator'
		},{
			key: 'user',
			val: 'User'
		}]
	};


	$scope.save = (form) => {
		api.call('events/edit', {
			user_id: form.user_id,
			role: form.role.key,
			status: form.status.key,
			first_name: form.firstname,
			last_name: form.lastname,
			email: form.email,
			password: form.password,
		})
		.success((res)=>{
			if (res.status == 1) {
				modal.$promise.then(modal.hide);
				Alert.info('User edited');
				getevents(undefined,  {
					status: $scope.filter.status.key
			});
			}
			if (res.status == 0) {
				$scope.errors = res.messages;
			}

		});
	}
	
	var quill = "";
	
	$scope.initForm = () => {
        $timeout(function() {
			
			quill = new Quill('#desc-editor', {
				//modules: { toolbar: '#toolbar' },
				theme: 'snow'
			});
			
			if($scope.method == 'insert') {
				quill.container.firstChild.innerHTML = '<p>Please enter description.</p>';
			} else {
				quill.container.firstChild.innerHTML = $scope.event_info.description;
				quill.disable();
			}
        });
	};

	$scope.add = () => {
		$scope.$broadcast('events:add');
	}

	$scope.$on('events:add', function() {
		$scope.errors = {};
		$scope.form = angular.copy($scope.form);
		$scope.method = 'insert';
		modal = $modal({
			scope: $scope,
			templateUrl: 'create-events.modal.html',
			title: 'Add Event',
			show: false
		});
		/*
		modal.$scope.form = {
			event_name : "wwang test",
			location : "Irving, Tx",
			
			date : {
					year: "2020",
					month: "06",
					day: "15",
					hours: "09",
					minutes: "30",
			},
			end_date : {
					year: "2020",
					month: "06",
					day: "15",
					hours: "12",
					minutes: "00",
			},
			
			event_at: "2020-06-15 09:30:00",
			period_from : {
				hours: "09",
				minutes: "30",
			},
			
			event_to: "2020-06-15 12:00:00",
			period_to : {
				hours: "12",
				minutes: "00",
			},
			
			description : "WWang Dev Test Event",
			
			seats : 100,
		};
		*/
		modal.$promise.then(modal.show);

		modal.$scope.customImage = () => {
			console.log('yoddle 1', modal.$scope.image_preview_custom);
			console.log('yoddle 2', modal.$scope.form.event_image);
			
			modal.$scope.image_preview = null;
			modal.$scope.image_preview_custom = modal.$scope.form.event_image;
		};
	});

	$scope.action = (method, form) => {
		if (method == "insert") {
			
			var stop_submit = false;

			var date = moment(form.date.year + '-' + form.date.month + '-' + form.date.day);
			var period_from = moment(now.format('YYYY-MM-DD').toString() + ' ' + form.period_from.hours + ':' + form.period_from.minutes);
			var period_to  = moment(now.format('YYYY-MM-DD').toString()  + ' ' + form.period_to.hours + ':' + form.period_to.minutes);

			$scope.errors = {};
			if(!date.isValid()){
				Alert.info('Please enter valid date');
				$scope.errors['date'] = 'Please enter valid date';
				stop_submit = true;
			}

			if(!period_from.isValid()){
				Alert.info('Please enter valid time when boarding starts');
				$scope.errors['period_from'] = 'Please enter valid time when boarding starts';
				stop_submit = true;
			}

			if(!period_to.isValid()){
				Alert.info('Please enter valid time when boarding end');
				$scope.errors['period_to'] = 'Please enter valid time when boarding end';
				stop_submit = true;
			}

			if(period_from.isValid() && period_to.isValid()){
				if(parseInt(period_to.format('HH')) < parseInt(period_from.format('HH'))){
					Alert.info('Boarding time is not valid');
					$scope.errors['period_to'] = 'Boarding time is not valid';
					stop_submit = true;
				}
			}
			

			if(stop_submit){
				return false;
			}
			
			var data = {
				'name' : form.event_name,
				'date': date.toString(),
				'period_from': period_from.format('HH:mm'),
				'period_to': period_to.format('HH:mm'),
				'location' : form.location,
				'description' : quill.container.firstChild.innerHTML,
				// 'event_image' : form.event_image,
				'seats' : form.seats

			};

			api.call('events/create', data)
				.success((res) => {
					if (res.status == 1) {

						if(form.event_image){
							upload(form.event_image, res.responseData.id, "new_event");
						}

						modal.hide();
						Alert.success('Event added');
						$route.reload();
					}
					if (res.status == 0) {
						$scope.errors = res.messages;
					}
				});
		} else if (method == "edit") {
			
			var stop_submit = false;
			
			var date = moment(form.date.year + '-' + form.date.month + '-' + form.date.day);
			var period_from = moment(date.format('YYYY-MM-DD').toString() + ' ' + form.period_from.hours + ':' + form.period_from.minutes);
			var period_to  = moment(date.format('YYYY-MM-DD').toString()  + ' ' + form.period_to.hours + ':' + form.period_to.minutes);
			
			$scope.errors = {};
			
			if(!date.isValid()){
				Alert.info('Please enter valid date');
				$scope.errors['date'] = 'Please enter valid date';
				stop_submit = true;
			}

			if(!period_from.isValid()){
				Alert.info('Please enter valid time when boarding starts');
				$scope.errors['period_from'] = 'Please enter valid time when boarding starts';
				stop_submit = true;
			}

			if(!period_to.isValid()){
				Alert.info('Please enter valid time when boarding end');
				$scope.errors['period_to'] = 'Please enter valid time when boarding end';
				stop_submit = true;
			}

			if(period_from.isValid() && period_to.isValid()){
				if(parseInt(period_to.format('HH')) < parseInt(period_from.format('HH'))){
					Alert.info('Boarding time is not valid');
					$scope.errors['period_to'] = 'Boarding time is not valid';
					stop_submit = true;
				}
			}
			
			var data = {
				'id' : form.event_id,
				'name' : form.event_name,
				'date': date.toString(),
				'period_from': period_from.format('HH:mm'),
				'period_to': period_to.format('HH:mm'),
				'location' : form.location,
				'description' : form.description,
				// 'event_image' : form.event_image,
				'seats' : form.seats

			};
			

			if(stop_submit){
				return false;
			}

			api.call('events/edit/' + form.event_id, data)
			.success((res) => {				
				if (res.status == 1) {
					// upload only when change event image 
					if(form.event_image && form.event_image['type']){
					
						upload(form.event_image, form.event_id, "edit_event");
					}

					modal.hide();
					Alert.success('Event updated');
					$route.reload();
				}
				if (res.status == 0) {
					$scope.errors = res.messages;
				}
			});


		}
	};

	var upload = function(file, event_id, is_new_event) {
		console.log('file',file);	
		Upload.upload({
			url: 'api/admin/upload',
			method: 'POST',
			data: {
				file: file,
				data: {
					type: 'event',
					event_id: event_id,
					name: file.name.substr(0, file.name.lastIndexOf('.')),
					is_new_event : is_new_event,
				}
			}
		}).then(
			(res) => {
				console.log('Upload success : ', res);
				modal.hide();
				Alert.success('Event added')
				$scope.$broadcast('events:refresh');
			},
			(res) => {
				console.log('Upload error : ', res);
			},
			(event) => {
				var progressPercentage = parseInt(100.0 * event.loaded / event.total);
				console.log('progress: ' + progressPercentage + '%');
			}
		);
	}
	$scope.delete = (event_id) => {
		api.call("events/delete/" + event_id)
			.success((res) => {
				if (res.status == 1) {
					Alert.success('Event deleted');
					$route.reload();
				}
				if (res.status == 0) {
					$scope.errors = res.messages;
				}
			});
	};
});
