@extends('layouts.master')

@section('content')

<main class="page-view" ng-controller="ProfileController as profile">
    <div class="settings-section">
        <div class="container">
            <form class="form-horizontal" novalidate name="form.profile">
                <fieldset>
                    <!-- Form Name -->
                    <legend>Profile</legend>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">Email</label>
                        <div class="col-md-5">
                            <input id="email" name="email" type="text" placeholder="email" class="form-control input-md" required="" ng-model="email" ng-init="email = '{{$data['email']}}'">
                            <span ng-message-exp="errors.email" class="help-inline" ng-bind="errors.email"></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="first_name">First name</label>
                        <div class="col-md-5">
                            <input id="email" name="first_name" type="text" placeholder="first name" class="form-control input-md" required="" ng-model="first_name" ng-init="first_name = '{{$data['first_name']}}'">
                            <span ng-message-exp="errors.first_name" class="help-inline" ng-bind="errors.first_name"></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="last_name">Last name</label>
                        <div class="col-md-5">
                            <input id="email" name="last_name" type="text" placeholder="last name" class="form-control input-md" required="" ng-model="last_name" ng-init="last_name = '{{$data['last_name']}}'">
                            <span ng-message-exp="errors.last_name" class="help-inline" ng-bind="errors.last_name"></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="company">Company</label>
                        <div class="col-md-5">
                            <input id="email" name="company" type="text" placeholder="company" class="form-control input-md" required="" ng-model="company" ng-init="company = '{{$data['company']}}'">
                            <span ng-message-exp="errors.company" class="help-inline" ng-bind="errors.company"></span>
                        </div>
                    </div>

                    <div class="form-group avatar-settings">
                        <label class="col-md-4 control-label">Your Avatar</label>
                        <div class="col-md-6">
                            <button class="btn btn-gray"
                                 ngf-select="avatarUpload()"
                                 ng-model="avatar"
                                 name="file"
                                 type="file"
                                 ngf-pattern="'image/*'"
                                 accept="image/*"
                                 ngf-max-size="20MB">
                                 Upload
                             </button>
                             <span class="file-name">@{{avatar.name}}</span>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="update_profile"></label>
                        <div class="col-md-4">
                            <a id="update_profile" class="btn btn-primary xs-btn-full" ng-click="profile.updateProfile()">Update Profile</a>
                        </div>
                    </div>
                </fieldset>
            </form>

            <form class="form-horizontal" novalidate name="form.password">
                <fieldset>
                    <legend>Password</legend>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="password">Old Password</label>
                        <div class="col-md-5">
                            <input id="email" name="old_password" type="password" placeholder="password" class="form-control input-md" required="" ng-model="old_password">
                            <span ng-message-exp="errors.old_password" class="help-inline" ng-bind="errors.old_password"></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="password">New Password</label>
                        <div class="col-md-5">
                            <input id="email" name="new_password" type="password" placeholder="new password" class="form-control input-md" required="" ng-model="new_password">
                            <span ng-message-exp="errors.new_password" class="help-inline" ng-bind="errors.new_password"></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="repeat_password">Confirm password</label>
                        <div class="col-md-5">
                            <input id="new_password_repeat" name="new_password_repeat" type="password" placeholder="repeat password" class="form-control input-md" required="" ng-model="new_password_repeat">
                            <span ng-message-exp="errors.new_password_repeat" class="help-inline" ng-bind="errors.new_password_repeat"></span>
                        </div>
                    </div>


                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="update_password"></label>
                        <div class="col-md-4">
                            <a id="update_password" class="btn btn-primary xs-btn-full" ng-click="profile.updatePassword()">Change Password</a>
                        </div>
                    </div>
                </fieldset>
            </form>
                <legend>Account</legend>
                <div class="form-group">
                    <p class="col-md-4">If you choose to close your account it wil be deleted and you will not be able to log in with current credentials.</p>
                    <div class="col-md-5">
                        <button class="btn btn-primary xs-btn-full" ng-click="profile.closeAccount()">Close Account</button>
                    </div>
                </div>
        </div>
    </div>
</main>



@stop
