<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('auth/password', 'PagesController@passwordLogin');
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::get('auth/logout', 'Auth\AuthController@logOutWeb');
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::get('auth/verify/{confirmation_code}', 'Auth\AuthController@verify');

    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');

    Route::any('paypal/success', 'PayPalController@success');
    Route::any('paypal/cancel', 'PayPalController@cancel');
    Route::any('paypal/ipn', 'PayPalController@ipn');

    Route::any('webhook/', 'WebhookController@send');
    Route::any('paypal/subscribe/{token}/{package_id}', 'BillingController@subscribePayPal');
    Route::get('/', 'PagesController@index');
	
	Route::any('stripe/webhook', 'PagesController@stripeWebhook');

    Route::group( ['middleware' => 'auth'], function() {

		Route::any('/book-flight', 'PagesController@flights');

		Route::any('/charter-flight', 'PagesController@charterFlight');
		Route::any('/select-aircraft', 'PagesController@selectAircraft');

		Route::any('/faq', 'PagesController@faq');
		Route::any('/contact', 'PagesController@contactPage');
		Route::any('/terms', 'PagesController@termsConditionsIOS');
		Route::any('/terms-conditions', 'PagesController@termsConditions');
		Route::any('/request-submited', 'PagesController@submited');

		Route::any('/flight/{id}', 'PagesController@flightDetails');

		Route::any('membership', 'PagesController@membership');

		Route::any('events', 'PagesController@events');
		Route::any('events/{id}', 'PagesController@eventDetails');




		Route::group(['middleware' => 'auth'], function () {

			// Route::any('flight-status', 'PagesController@flightStatus');

			Route::any('flights-alerts', 'PagesController@flightsAlerts');

			Route::any('hobo-squad', 'PagesController@hoboSquad');

			Route::get('profile', 'ProfileController@index');

			Route::get('verify/{confirmation_code}', 'VerifyController@verify');

			Route::post('upload/', 'UploadsController@upload');

		});
	});

	Route::group(['prefix' => 'api'], function(){

		// Route::post('auth/register', 'Auth\AuthController@register');
		
		Route::post('auth/login', 'Auth\AuthController@login');
		Route::post('auth/logout', 'Auth\AuthController@logOut');
		
		// Route::post('auth/password-login', 'Auth\AuthController@passwordLogin');
		
		Route::post('auth/token/check', 'Auth\AuthController@token');

		// Route::post('auth/fb/register', 'Auth\FacebookController@fb');
		// Route::post('auth/fb/login', 'Auth\FacebookController@fb');

		Route::post('auth/password/reset', 'Auth\PasswordController@reset');

		Route::post('installation/register', 'InstallationController@installation');

		Route::post('flight/request', 'BookingCharterController@get');

		Route::post('cities/search', 'AirportsController@searchCities');

		Route::post('location/get', 'LocationController@getLocation');

		Route::post('notifications/get', 'NotificationsController@get');
		Route::post('notifications/readed', 'NotificationsController@readed');

		Route::post('settings/notifications/status', 'SettingsNotifications@status');
		Route::post('settings/notifications/on', 'SettingsNotifications@on');
		Route::post('settings/notifications/off', 'SettingsNotifications@off');

		Route::post('packages/all', 'PackagesController@getPackages');
		Route::post('packages/set', 'PackagesController@setPackage');
		Route::post('packages/user', 'PackagesController@getUserPackage');
		Route::any('packages/delete/{id}', 'PackagesController@delete');
		Route::any('packages/edit/{id}', 'PackagesController@edit');
		Route::any('packages/create', 'PackagesController@create');
		
		Route::post('packages/updatePackage', 'PackagesController@updatePackage');

		Route::post('paypal/subscribe/new', 'BillingController@subscribePending');
		Route::post('stripe/getCustomerId', 'BillingController@getCustomerId');
		Route::post('stripe/createSubscription', 'BillingController@createSubscription');
		
		Route::any('contact/membership', 'ContactController@membership');
		Route::any('contact/apply-membership', 'ContactController@applyMembership');
		Route::any('contact/form', 'ContactController@contact');
		
		

		Route::any('events/create', 'EventsController@create');
		Route::any('events/getAll', 'EventsController@getAll');
		Route::any('events/{id}', 'EventsController@get');
		Route::any('events/delete/{id}', 'EventsController@delete');
		Route::any('events/edit/{id}', 'EventsController@edit');
		
		Route::any('reservation', 'EventsController@reservation' );
		
		Route::any('membership', 'ContactController@getMembership' );


		Route::any( 'mailchimp/subscribe', 'PagesController@mailchimpSubscribe' );

		Route::any('promo', function(){

			return \App\Http\Response\Response::success([
				[
					'image' => url('data/images/promo/promo1.png')
				]
			]);

		});
	});


	Route::group( ['prefix' => 'api', 'middleware' => 'membership'], function(){
		Route::post('flights/get', 'FlightsController@get');
		Route::post('flights/get/details', 'FlightsController@getById');
	});


	Route::group(['prefix' => 'api', 'middleware' => 'api'], function(){

		Route::post('profile/update', 'ProfileController@updateProfile');
		Route::post('password/update', 'ProfileController@updatePassword');

		Route::post('alerts/add', 'AlertsController@add');
		Route::post('alerts/get', 'AlertsController@get');
		Route::post('alerts/del', 'AlertsController@del');

		Route::post('booking/add',  'BookingsController@add');
		Route::post('booking/web/add', 'BookingWebController@add');
		Route::post('booking/web/sendEmail', 'BookingWebController@sendEmail');
		Route::post('booking/get/all', 'BookingsController@getAll');
		Route::post('booking/get/details', 'BookingsController@get');

		Route::post('booking/custom', 'BookingCharterController@book');
		Route::post('booking/custom/pay', 'BookingCharterController@finishBooking');
		Route::post('booking/custom/pay/web', 'BookingCharterController@finishBookingWeb');

		Route::post('package/set', 'PackagesController@setPackage');
		Route::post('package/user', 'PackagesController@getUserPackage');
		Route::get('package/delete', 'PackagesController@getUserPackage');
		Route::get('package/create', 'PackagesController@getUserPackage');

		Route::post('notifications/del', 'NotificationsController@del');

		Route::post('pack/get', 'Pack\PackController@get');
		Route::post('pack/edit', 'Pack\PackController@edit');
		Route::post('pack/connect', 'Pack\PackController@connect');
		Route::post('pack/disconnect', 'Pack\PackController@disconnect');

		Route::post('settings/close', 'SettingsController@close');
		Route::post('settings/change/password', 'SettingsController@password');


		Route::post('paypal/subscribe/new/web', 'BillingController@subscribePendingWeb');
	});

	Route::post('upload/avatar/{token}', 'UploadsController@uploadAvatarMobile');

	Route::group(['prefix' => 'api', 'middleware' => 'auth'], function(){

		Route::post('settings/profile/update', 'ProfileController@updateProfile');
		Route::post('settings/password/update', 'ProfileController@updatePassword');
		Route::post('upload/', 'UploadsController@upload');


	});

	Route::get('logs/', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


	Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){

		Route::get('/', 'Admin\DashboardController@index');


	});

	Route::group(['prefix' => 'api/admin', 'middleware' => 'admin'], function(){

		Route::any('flights/', 'Admin\FlightsController@get');
		Route::post('flights/add', 'Admin\FlightsController@add');
		Route::post('flights/edit', 'Admin\FlightsController@edit');
		Route::post('flights/delete', 'Admin\FlightsController@delete');
		Route::get('flights/get/{flight_id}', 'Admin\FlightsController@getById');


		Route::get('aircrafts/', 'Admin\AircraftsController@get');
		Route::post('aircrafts/add', 'Admin\AircraftsController@add');
		Route::post('aircrafts/edit', 'Admin\AircraftsController@edit');
		Route::get('aircrafts/get/{aircraft_id}', 'Admin\AircraftsController@getById');
		Route::get('aircrafts/list', 'Admin\AircraftsController@listAll');
		Route::delete('aircrafts/delete/{aircraft_id}', 'Admin\AircraftsController@deleteById');

		Route::get('aircrafts/images/{aircraft_id}', 'Admin\AircraftsImagesController@get');
		Route::get('aircrafts/images/delete/{image_id}', 'Admin\AircraftsImagesController@delete');
		Route::post('aircrafts/images/default', 'Admin\AircraftsImagesController@setDefault');


		Route::post('upload', 'Admin\UploadsController@upload');


		Route::any('bookings/get', 'Admin\BookingsController@get');
		Route::post('booking/get/details', 'Admin\BookingsController@details');


		Route::post('bookings/accept', 'Admin\BookingsController@accept');
		Route::post('bookings/decline', 'Admin\BookingsController@decline');

		Route::any('bookings/get/custom', 'Admin\BookingsCharterController@get');
		Route::post('booking/charter/offer', 'Admin\BookingsCharterController@offer');
		Route::post('booking/charter/approve', 'Admin\BookingsCharterController@approve');
		Route::post('booking/charter/decline', 'Admin\BookingsCharterController@decline');

		Route::post('airports/search', 'Admin\AirportsController@searchAirportsByIata');


		Route::post('notifications/add', 'Admin\NotificationsController@add');
		Route::any('notifications/get', 'Admin\NotificationsController@get');


		Route::any('users/', 'Admin\UsersController@get');

		Route::any('users/operators', 'Admin\UsersController@getOperators');
		Route::any('users/user/{user_id}', 'Admin\UsersController@user');
		Route::any('users/edit', 'Admin\UsersController@edit');
		Route::any('users/create', 'Admin\UsersController@create');
		Route::any('users/delete/{user_id}', 'Admin\UsersController@delete');

		Route::get('promo/get', 'Admin\SettingsPromoController@get');
		Route::post('promo/move', 'Admin\SettingsPromoController@move');

		Route::post('password/change-password', 'Admin\PasswordController@changePassword');


	});
