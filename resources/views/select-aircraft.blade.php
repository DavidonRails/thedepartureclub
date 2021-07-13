@extends('layouts.master')

@section('content')

<main class="page-view">
	<section class="single-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="section-title">
						<h1 class="h1">Select Aircraft</h1>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="aircraft-box">
					<article>
						<div>
							<h1>Turbo Prop</h1>
							<p class="intro-paragraph">Starting at $9.000</p>
							<div class="arrow">
								<i class="ti-arrow-right"></i>
							</div>
						</div>
					</article>
				</div>
				<div class="aircraft-box">
					<article>
						<div>
							<h1>Turbo Prop</h1>
							<p class="intro-paragraph">Starting at $8.000</p>
							<div class="arrow">
								<i class="ti-arrow-right"></i>
							</div>
						</div>
					</article>
				</div>
				<div class="aircraft-box">
					<article>
						<div>
							<h1>Turbo Prop</h1>
							<p class="intro-paragraph">Starting at $11.000</p>
							<div class="arrow">
								<i class="ti-arrow-right"></i>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div><!-- /.container -->
	</section>
</main>

@stop
