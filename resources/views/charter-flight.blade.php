@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="CharterFlightController">
	<div class="page-cover" style="background-image: url(images/page-cover.png);">
		<div class="cover-title">
			<h1>Charter</h1>
			<p>Need a lift? Search for empty one-way private flights and score on amazing deal!</p>
		</div>
	</div>

	<section class="single-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<button class="btn btn-primary" ng-click="request()" ng-if="aircrafts.length == 0">Request flight</button>
				</div>
				<div class="col-md-12">

					<ul class="row">

						<li class="col-md-4 col-sm-6" ng-repeat="aircraft in aircrafts">
							<a href="javascript:void(0)" class="product-card">
								<article>
									<div class="product-img">
										<img ng-src="@{{ aircraft.image_path }}" alt="">
										<div class="charter-name">
											@{{ aircraft.name }}
										</div>
									</div>
									<div class="product-info charter-flight">
										<table>
											<tr>
												<td class="data-cell">
													<div class="data">
														<span>@{{ aircraft.seats }}</span>
														<small>SEATS</small>
													</div>
												</td>
												<td class="data-cell">
													<div class="data">
														<span>TBD</span>
														<small>PRICE</small>
													</div>
												</td>
											</tr>
										</table>
										<button class="btn-request" ng-click="requestFlight(aircraft.aircraft_id)">
											Request Aircraft
										</button>
									</div>
								</article>
							</a>
						</li>
						{{--<li class="col-md-4 col-sm-6">--}}
							{{--<a href="javascript:void(0)"--}}
								{{--class="product-card"--}}
								{{--data-template-url="charter-flight-modal.html"--}}
								{{--bs-modal--}}
								{{--data-animation="am-fade"--}}
								{{--data-container="body">--}}
								{{--<article>--}}
									{{--<div class="product-img">--}}
										{{--<img src="images/products/airplane01.png" alt="">--}}
										{{--<div class="charter-name">--}}
											{{--Pilatus PC-12--}}
										{{--</div>--}}
									{{--</div>--}}
									{{--<div class="product-info charter-flight">--}}
										{{--<table>--}}
											{{--<tr>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>6</span>--}}
														{{--<small>SEATS</small>--}}
													{{--</div>--}}
												{{--</td>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>$950</span>--}}
														{{--<small>PRICE</small>--}}
													{{--</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
										{{--</table>--}}
										{{--<button class="btn-request">--}}
											{{--Request Aircraft--}}
										{{--</button>--}}
									{{--</div>--}}
								{{--</article>--}}
							{{--</a>--}}
						{{--</li>--}}
						{{--<li class="col-md-4 col-sm-6">--}}
							{{--<a href="javascript:void(0)"--}}
								{{--class="product-card"--}}
								{{--data-template-url="charter-flight-modal.html"--}}
								{{--bs-modal--}}
								{{--data-animation="am-fade"--}}
								{{--data-container="body">--}}
								{{--<article>--}}
									{{--<div class="product-img">--}}
										{{--<img src="images/products/airplane01.png" alt="">--}}
										{{--<div class="charter-name">--}}
											{{--Pilatus PC-12--}}
										{{--</div>--}}
									{{--</div>--}}
									{{--<div class="product-info charter-flight">--}}
										{{--<table>--}}
											{{--<tr>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>6</span>--}}
														{{--<small>SEATS</small>--}}
													{{--</div>--}}
												{{--</td>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>$950</span>--}}
														{{--<small>PRICE</small>--}}
													{{--</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
										{{--</table>--}}
										{{--<button class="btn-request">--}}
											{{--Request Aircraft--}}
										{{--</button>--}}
									{{--</div>--}}
								{{--</article>--}}
							{{--</a>--}}
						{{--</li>--}}
						{{--<li class="col-md-4 col-sm-6">--}}
							{{--<a href="javascript:void(0)"--}}
								{{--class="product-card"--}}
								{{--data-template-url="charter-flight-modal.html"--}}
								{{--bs-modal--}}
								{{--data-animation="am-fade"--}}
								{{--data-container="body">--}}
								{{--<article>--}}
									{{--<div class="product-img">--}}
										{{--<img src="images/products/airplane01.png" alt="">--}}
										{{--<div class="charter-name">--}}
											{{--Pilatus PC-12--}}
										{{--</div>--}}
									{{--</div>--}}
									{{--<div class="product-info charter-flight">--}}
										{{--<table>--}}
											{{--<tr>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>6</span>--}}
														{{--<small>SEATS</small>--}}
													{{--</div>--}}
												{{--</td>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>$950</span>--}}
														{{--<small>PRICE</small>--}}
													{{--</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
										{{--</table>--}}
										{{--<button class="btn-request">--}}
											{{--Request Aircraft--}}
										{{--</button>--}}
									{{--</div>--}}
								{{--</article>--}}
							{{--</a>--}}
						{{--</li>--}}
						{{--<li class="col-md-4 col-sm-6">--}}
							{{--<a href="javascript:void(0)"--}}
								{{--class="product-card"--}}
								{{--data-template-url="charter-flight-modal.html"--}}
								{{--bs-modal--}}
								{{--data-animation="am-fade"--}}
								{{--data-container="body">--}}
								{{--<article>--}}
									{{--<div class="product-img">--}}
										{{--<img src="images/products/airplane01.png" alt="">--}}
										{{--<div class="charter-name">--}}
											{{--Pilatus PC-12--}}
										{{--</div>--}}
									{{--</div>--}}
									{{--<div class="product-info charter-flight">--}}
										{{--<table>--}}
											{{--<tr>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>6</span>--}}
														{{--<small>SEATS</small>--}}
													{{--</div>--}}
												{{--</td>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>$950</span>--}}
														{{--<small>PRICE</small>--}}
													{{--</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
										{{--</table>--}}
										{{--<button class="btn-request">--}}
											{{--Request Aircraft--}}
										{{--</button>--}}
									{{--</div>--}}
								{{--</article>--}}
							{{--</a>--}}
						{{--</li>--}}
						{{--<li class="col-md-4 col-sm-6">--}}
							{{--<a href="javascript:void(0)"--}}
								{{--class="product-card"--}}
								{{--data-template-url="charter-flight-modal.html"--}}
								{{--bs-modal--}}
								{{--data-animation="am-fade"--}}
								{{--data-container="body">--}}
								{{--<article>--}}
									{{--<div class="product-img">--}}
										{{--<img src="images/products/airplane01.png" alt="">--}}
										{{--<div class="charter-name">--}}
											{{--Pilatus PC-12--}}
										{{--</div>--}}
									{{--</div>--}}
									{{--<div class="product-info charter-flight">--}}
										{{--<table>--}}
											{{--<tr>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>6</span>--}}
														{{--<small>SEATS</small>--}}
													{{--</div>--}}
												{{--</td>--}}
												{{--<td class="data-cell">--}}
													{{--<div class="data">--}}
														{{--<span>$950</span>--}}
														{{--<small>PRICE</small>--}}
													{{--</div>--}}
												{{--</td>--}}
											{{--</tr>--}}
										{{--</table>--}}
										{{--<button class="btn-request">--}}
											{{--Request Aircraft--}}
										{{--</button>--}}
									{{--</div>--}}
								{{--</article>--}}
							{{--</a>--}}
						{{--</li>--}}
					</ul>
				</div>
			</div>
		</div><!-- /.container -->
	</section>
	@include("charterflight.ng-templates")

</main>
@stop
