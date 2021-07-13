


import '../Api';
import '../Alerts';
import "./Dashboard";
import "./Flights";
import "./Aircrafts";
import "./AircraftsImages";
import "./Bookings";
import "./BookingsCharter";
import "./Users";
import "./Notifications";
import "./Settings";
import "./SettingsPromo";
import "./Common";
import "./Password";
// import "./Pack";
import "./Packages"
import "./Events"

angular.module('hoboAdmin', [
	'templates',
	'ngRoute',
	'ngSanitize',
	'mgcrea.ngStrap',
	'ngFileUpload',
	'ui.select',
	'hobo.api',
	'hobo.ui.alerts',
	'hoboAdmin.dashboard',
	'hoboAdmin.flights',
	//'hoboAdmin.pack',
	'hoboAdmin.aircrafts',
	'hoboAdmin.aircraftsImages',
	'hoboAdmin.bookings',
	'hoboAdmin.packages',
	'hoboAdmin.events',
	'hoboAdmin.bookingsCharter',
	'hoboAdmin.users',
	'hoboAdmin.notifications',
	'hoboAdmin.settings',
	'hoboAdmin.common',
	'hoboAdmin.password',
])

	.config(($routeProvider, $modalProvider, $datepickerProvider, $timepickerProvider, $typeaheadProvider) => {

		$routeProvider.when('/', {
			template: '<h1>Dashboard</h1>',
			controller: 'DashboardController as dashboard'
		});

		$routeProvider.when('/flights', {
			templateUrl: 'flights.html',
			controller: 'FlightsController as flights'
		});

		$routeProvider.when('/aircrafts', {
			templateUrl: 'aircrafts.html',
			controller: 'AircraftsController as aircrafts'
		});

		$routeProvider.when('/aircrafts/images/:aircraft_id', {
			templateUrl: 'aircrafts_images.html',
			controller: 'AircraftsImagesController as aircraft_images'
		});

		$routeProvider.when('/bookings', {
			templateUrl: 'bookings.html',
			controller: 'BookingsController as bookings'
		});

		$routeProvider.when('/bookings-charter', {
			templateUrl: 'bookings-charter.html',
			controller: 'BookingsCharterController as bookings'
		});

		$routeProvider.when('/notifications', {
			templateUrl: 'notifications.html',
			controller: 'NotificationsController as notifications'
		});

		$routeProvider.when('/users', {
			templateUrl: 'users.html',
			controller: 'UsersController as users'
		});
		
		$routeProvider.when('/settings', {
			templateUrl: 'settings.html',
			controller: 'SettingsController as settings'
		});

		$routeProvider.when('/password', {
			templateUrl: 'password.html',
			controller: 'PasswordController as password'
		});

		$routeProvider.when('/settings/promo', {
			templateUrl: 'settings.promo.html',
			controller: 'SettingsPromoController as promo'
		});

		$routeProvider.when('/pack', {
			templateUrl: 'pack.html',
		 	controller: 'PackController as pack'
		});

		$routeProvider.when('/packages', {
			templateUrl: 'packages.html',
			controller: 'PackagesController as packages'
		});
		
		$routeProvider.when('/events', {
			templateUrl: 'events.html',
			controller: 'EventsController as events'
		});
		



		
		$routeProvider.otherwise({
			redirectTo: '/'
		});

		angular.extend($modalProvider.defaults, {
			animation: 'am-flip-x'
		});


		angular.extend($datepickerProvider.defaults, {
			dateFormat: 'MM/dd/yyyy',
			startWeek: 1,
			timezone: 'UTC'
		});

		angular.extend($timepickerProvider.defaults, {
			timeFormat: 'HH:mm',
			roundDisplay: true
		});

		angular.extend($typeaheadProvider.defaults, {
			minLength: 3,
			limit: 8,
			trimValue: false
		});


	})


	.controller('AdminController', () => {


	})

	.filter('dateToISO', function() {
		return function(input) {
			return new Date(input).toISOString();
		};
	});

