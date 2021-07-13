@extends('layouts.master')

@section('content')
<main class="page-view" ng-controller="PasswordLoginController">
    <section class="single-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h1 class="h1">Password</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
                    <form>
                            <!-- Password input-->
                            <div class="form-group">
                                <input type="password" placeholder="Password" class="form-control input-md" required="" ng-model="form.password">
                                <span class="form-control-ico ti-lock"></span>
                                <span ng-message-exp="errors.password" class="help-inline" ng-bind="errors.password"></span>
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="register"></label>
                                <a id="register" class="btn btn-primary btn-block" ng-click="sendPassword()">Login</a>
                            </div>
                    </form>
                </div>
            </div>


        </div>
    </section>

</main>
@stop