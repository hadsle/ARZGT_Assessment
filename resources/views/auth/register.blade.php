@extends('layout.main-layout')
@section('body')
<div class="row mb-3">
    <form action="{{route('postRegister')}}" method="POST" class="col-md-6 col-xs-12 offset-md-3 auth-form"
        id="regitration_form">
        @csrf
        <div class="form-title">
            SIGN UP
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name"
                placeholder="Enter your name">
            @if($errors->any('name'))
            <span class="text-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" value="{{old('email')}}" id="email" name="email"
                aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            @if($errors->any('email'))
            <span class="text-danger">{{$errors->first('email')}}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" autocomplete="false" id="password"
                placeholder="Password">
            @if($errors->any('password'))
            <span class="text-danger">{{$errors->first('password')}}</span>
            @endif
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" autocomplete="false"
                id="confirm_password" placeholder="Confirm Password">
            @if($errors->any('confirm_password'))
            <span class="text-danger">{{$errors->first('confirm_password')}}</span>
            @endif
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" value="{{old('phone_number')}}" id="phone_number" name="phone_number"
                placeholder="Enter your phone_number">
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" value="{{old('department')}}" id="department" name="department"
                placeholder="Enter your department name">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="gender" value="{{old('gender')}}" name="gender">
                <option value="">Choose...</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" value="{{old('role')}}" name="role">
                <option value="">Choose...</option>
                <option value="admin">Admin</option>
                <option value="user">Normale User</option>
            </select>
        </div>
        <div class="form-check">
            <input type="checkbox" {{(old('terms'))?'checked':''}} name="terms" id="terms" class="form-check-input">
            <label class="form-check-label" for="terms_check">Check our <a href="#">terms</a> and <a
                    href="#">conditions</a></label>

        </div>
        <div id="terms_error"></div>
        @if($errors->any('terms'))
        <span class="text-danger">{{$errors->first('terms')}}</span>
        @endif
        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_KEY')}}"
            data-callback="recaptchaDataCallbackRegister" data-expired-callback="recaptchaExpireCallbackRegister"></div>

        <input type="hidden" name="grecaptcha" id="hiddenRecaptchaRegister">
        <div id="hiddenRecaptchaRegisterError"></div>
        @if($errors->any('grecaptcha'))
        <span class="text-danger">{{$errors->first('grecaptcha')}}</span>
        @endif
        <div><button type="submit" class="btn btn-primary mt-2">Submit</button>&nbsp; Already have an account <a
                href="">sign in</a> here</div </form>
</div>
@endsection