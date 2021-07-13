@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="HoboSquadController">
	<section class="single-section">
		<div class="container">

			<div class="squad-header">
					<div class="col-sm-6">
						<div class="form-group">
							<h3 ng-show="mode == 'display'" ng-bind="hobo_pack_name" ng-init="hobo_pack_name = '{{$pack['owner']['pack_name']}}'"></h3>
							<input type="text" placeholder="Squad Name" class="form-control no-trans" ng-model="new_pack_name" ng-show="mode == 'edit'">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group text-right">
							<button class="btn btn-primary sm-btn-full no-trans" ng-click="edit()" ng-show="mode == 'display'">Edit Squad</button>
							<button class="btn btn-primary sm-btn-full no-trans" ng-click="save({{$pack['owner']['pack_id']}})" ng-show="mode == 'edit'">Save Squad</button>
						</div>
					</div>
			</div>

			<ul class="squad">
				<li class="sqad-item">
					<article>
						{{--<button class="remove-squad-item">--}}
							{{--<i class="ti-trash"></i>--}}
						{{--</button>--}}
						<div>
							<div class="squad-img" style="background-image: url({{url($me['avatar'])}});">
							</div>
							<div class="squad-name">
								{{$me['first_name']}} {{$me['last_name']}}
							</div>
						</div>
					</article>
				</li>
				@if(count($pack['owner']['members']))
					@foreach($pack['owner']['members'] as $member)
						<li class="sqad-item" id="member_{{$member['user_id']}}">

							<article>
								<button class="remove-squad-item" ng-click="remove({{$pack['owner']['pack_id']}}, {{$member['user_id']}}, '{{$member['first_name']}}', '{{$member['last_name']}}')">
									<i class="ti-trash"></i>
								</button>
								<div>
									<div class="squad-img" style="background-image: url({{url($member['avatar'])}});">
									</div>
									<div class="squad-name">
										{{$member['first_name']}} {{$member['last_name']}}
									</div>
								</div>
							</article>
						</li>
					@endforeach
				@endif
				<li class="sqad-item">
					<article>
						<div>
							<div class="squad-add">
								<a href="javascript:void(0)" ng-click="invite()"><i class="ti-plus"></i></a>
							</div>
							<div class="squad-name">
								Invite Friend
							</div>
						</div>
					</article>
				</li>
			</ul>

			<div class="row">
				<div class="col-md-12">
					@if(count($pack['owner']['members']) < 4)
						<p class="squad-message">Assemble your Departure Squad today! <br> Invite 3 friends to your Departure Squad to unlock exclusive discounts and freebie flights.<br>Please note that Departure Squad will not be active until all 4 members have joined.</p>
					@else
						<p class="squad-message">Assemble your Departure Squad today! <br> Invite 3 friends to your Departure Squad to unlock exclusive discounts and freebie flights.</p>
					@endif
				</div>
			</div>
		</div><!-- /.container -->

	</section>
</main>
@include("hobosquad.ng-templates")
@stop
