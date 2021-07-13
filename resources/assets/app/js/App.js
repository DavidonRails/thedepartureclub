
//main app

import './Api';
import './Auth';
import './Alerts';

import './Profile';
import './Flights';

import './Bookings';

import './Subscribe';

import './Membership'

import './FlightsAlerts'

import './CharterFlight';

import './HoboSquad';

import './Contact';
import './Events';

import './PasswordLogin';

angular.module('app', [
	'hobo.api',
	'hobo.auth',
	'hobo.profile',
	'hobo.flights',
	'hobo.subscribe',
	'hobo.bookings',
	'hobo.flightAlerts',
	'hobo.charterFlight',
	'hobo.membership',
	'hobo.hoboSquad',
	'hobo.events',
	'hobo.contact',
	'hobo.ui.alerts',
	'hobo.stripe',
	'ngAnimate',
	'ngCookies',
	'mgcrea.ngStrap',
	'slickCarousel',
	'rzModule'])

	.controller('AppController', function($scope, $rootScope, $modal, Alert, api, $cookies){
		var app = this;

		$scope.aboutConfig = {
		    enabled: true,
		    speed: 300,
		    slidesToShow: 3,
		    centerMode: true,
		    centerPadding: '9%',
			prevArrow: '<button type="button" data-role="none" class="slider-prev" aria-label="Previous" tabindex="0" role="button"><i class="ti-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slider-next" aria-label="Next" tabindex="0" role="button"><i class="ti-arrow-right"></i></button>',
		    autoplaySpeed: 3000,
			responsive: [
			    {
			      breakpoint: 1170,
			      settings: {
			        slidesToShow: 2
			      }
			    },
			    {
			      breakpoint: 992,
			      settings: {
			        slidesToShow: 1
			      }
			    }
			]
		};
		$scope.testimonialsConfig = {
		    enabled: true,
		    speed: 300,
		    dots: true,
			prevArrow: '<button type="button" data-role="none" class="slider-prev" aria-label="Previous" tabindex="0" role="button"><i class="ti-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slider-next" aria-label="Next" tabindex="0" role="button"><i class="ti-arrow-right"></i></button>',
		    autoplaySpeed: 3000
		};

		$scope.showRequestModal = function(){
			$modal({scope: $scope, template: 'request-modal.html', show: true, backdrop: "static", animation: "am-fade"});
		}

		$rootScope.auth = false;
		if(user != null){
			$rootScope.auth = true;
			$rootScope.user = JSON.parse(user);
		}


		var modal = null;
		app.login = () => {
			if(modal){
				modal.$promise.then(modal.hide);
				modal = null;
			}

			modal = $modal({
				scope: $scope,
				templateUrl: 'login-modal.html'
			});
		}

		app.register = function() {
			if(modal){
				modal.$promise.then(modal.hide);
				modal = null;
			}

			modal = $modal({
				scope: $scope,
				templateUrl: 'register-modal.html'
			});
		}
		app.password = () => {
			if(modal){
				modal.$promise.then(modal.hide);
				modal = null;
			}

			modal = $modal({
				scope: $scope,
				templateUrl: 'password-modal.html'
			});
		}

		$rootScope.$on('auth', (e, action) => {
			switch (action){
				case 'login':
					app.login();
					break;
			}
		});



		$scope.refreshSlider = function() {
			setTimeout(function() {
				$scope.$broadcast('rzSliderForceRender');
			}, 1)
		};


		$scope.subscribeModal = () => {
			var c = $cookies.get('subscribe_modal');
			if(typeof c != 'undefined'){
				return;
			}

			var subscribeModal = $modal({
				scope: $scope,
				templateUrl: 'subscribe-modal.html'
			});

			$cookies.put('subscribe_modal', 1, {
				expires: moment().add(30, 'day').toString()
			});

			subscribeModal.$scope.send = function(form){
				subscribeModal.$scope.errors = [];
				if(typeof form == 'undefined'){
					subscribeModal.$scope.errors = {
						email: 'Please enter valid email address'
					};
					return;
				}

				api.call('mailchimp/subscribe', {
					email: form.email,
					name: form.name,
					state: form.state,
				})
					.success((res) => {

						if(res.status == 1){
							subscribeModal.$promise.then(subscribeModal.hide);
							Alert.info('You are now subscribed to our newsletter');
							$cookies.put('subscribe_modal', 2);
						}else{
							subscribeModal.$scope.errors = {
								email: res.messages.message
							}
						}

					})
			}

			setTimeout(() => {
				subscribeModal.$promise.then(subscribeModal.show);
			}, 50)

		}
		// $scope.subscribeModal();
	})

	.directive('subscribe', function(Alert, api, $q){

		return {
			link: function ($scope) {


				$scope.send = () => {

					api.call('mailchimp/subscribe', {
						email: $scope.email
					})
						.success((res) => {

							if(res.status == 1){
								Alert.info('You are now subscribed to our newsletter');
								$scope.email = '';
							}else{
								Alert.info(res.messages.message);
							}

						})

				}

			}
		}

	})

	// About slider image switch
	$(".about-slider .avatar").hover(function() {
		$(this).toggleClass("fun-on").parent('.hobo-card').toggleClass("serious-on");
	});

	//Mobile menu trigger
	$("#menu-btn").click(function () {
	    $(this).toggleClass("is-clicked");
	    $(".main-menu").toggleClass("is-visible");
	});

	$(".main-menu").click(function () {
	    $(this).removeClass("is-visible");
	    $("#menu-btn").removeClass("is-clicked");
	});

	// Sticky Filter
	$(function(){
		filterSticky();
	});
	function filterSticky() {
		var sticky = $("#dealsFilter")
		if (sticky.length) {
			var	pos = sticky.offset().top - 68,
				win = jQuery(window);
			win.on("scroll", function() {
				win.scrollTop() >= pos ? sticky.addClass("fixed") : sticky.removeClass("fixed");
			});
		}
	}


	// Scroll to section
	$('[href^="#"]').off().on('click', function (e) {
	    if ($(this).attr('href') != '#') {
	        e.preventDefault();
	        $('body').animate({scrollTop: $($(this).attr('href')).offset().top}, 800);
	    }
	});

	//Preload Pages
	$(window).load(function() {
		$('#status').fadeOut();
		$('#pagePreload').delay(350).fadeOut(300);
		$('body').delay(350);
	})

	//hero Paralax
	$(document).ready(function(){
		var hero_image = $('.hero-image');
		$(window).scroll(function () {
			var st = this.pageYOffset;
			hero_image.css({'transform' : 'translate3d(0, ' + (st*.3) + 'px, 0)'});
		});
	});
