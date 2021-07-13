@extends('layouts.master')

@section('content')

<div class="container" ng-controller="RegisterController" style="margin-top: 80px">

    <form class="form-horizontal" novalidate name="form.register" ng-submit="register()">
        <fieldset>

            <!-- Form Name -->
            <legend>Register</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="first_name">First name</label>
                <div class="col-md-5">
                    <input id="first_name" name="first_name" type="text" placeholder="First name" class="form-control input-md" ng-model="first_name">
                    <span ng-message-exp="errors.first_name" class="help-inline" ng-bind="errors.first_name"></span>

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="last_name">Last name</label>
                <div class="col-md-5">
                    <input id="last_name" name="last_name" type="text" placeholder="Last name" class="form-control input-md" ng-model="last_name">
                    <span ng-message-exp="errors.last_name" class="help-inline" ng-bind="errors.last_name"></span>

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Email</label>
                <div class="col-md-5">
                    <input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" ng-model="email">
                    <span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>

                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Password</label>
                <div class="col-md-5">
                    <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" ng-model="password">
                    <span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.password"></span>

                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="facebook"></label>
                <div class="col-md-4">
                    <a id="facebook" class="btn btn-primary" ng-click="facebook()">Facebook</a>
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="register"></label>
                <div class="col-md-4">
                    <a id="register" class="btn btn-primary" ng-click="register()">Register</a>
                </div>
            </div>

        </fieldset>
    </form>

</div>

@stop