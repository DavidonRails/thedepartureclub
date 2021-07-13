@extends('layouts.master')

@section('content')
<main class="page-view" ng-controller="LoginController as login">
    <section class="single-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h1 class="h1">Login</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
                    <form novalidate name="form.login" ng-submit="login()">
                        {{--<div class="form-group">--}}
                            {{--<a href="javascript:void(0);" ng-click="facebook()" class="btn btn-fb btn-block">--}}
                                {{--<span>Login with Facebook</span>--}}
                            {{--</a>--}}
                        {{--</div>--}}

                        {{--<div class="form-group text-center">--}}
                            {{--or--}}
                        {{--</div>--}}
                            <!-- Text input-->
                            <div class="form-group">
                                <input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" required="" ng-model="email">
                                <span class="form-control-ico ti-email"></span>
                                <span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.email"></span>
                            </div>

                            <!-- Password input-->
                            <div class="form-group">
                                <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="" ng-model="password">
                                <span class="form-control-ico ti-lock"></span>
                                <span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.password"></span>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <input type="checkbox" id="check" name="remember" ng-model="remember">
                                            <label for="check">Remember me</label>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="form-group text-right">--}}
                                        {{--<a class="normal" href="javascript:void(0)" ng-click="app.password()">--}}
                                            {{--Forgot Password?--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="register"></label>
                                <a id="register" class="btn btn-primary btn-block" ng-click="login()">Login</a>
                            </div>
                            {{--<p>Don’t have an account?--}}
                                {{--<a class="normal" href="javascript:void(0)" ng-click="app.register()">--}}
                                    {{--Register--}}
                                {{--</a>--}}
                            {{--</p>--}}
                    </form>
                </div>
            </div>


        </div>
    </section>

</main>


@stop
