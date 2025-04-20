@extends('layouts.marketing')

@section('content')
<div class="container auth-form-wrap">
    <h1 class="text-center auth-page-title">{{ $pageTitle }}</h1>

    <fe-form
    action="{{ url('/password/reset') }}"
    form-id="password-reset-form"
    class="password-reset-form auth-form"
    submit-label="Reset Password"
    :submit-centered="true"
    :submit-ignore-errors="true">
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- E-Mail Address -->
        <input-textual
        label="E-Mail Address"
        name="email"
        value="{{ $email or old('email') }}"
        validation-message="{{ $errors->first('email') }}"></input-textual>

        <!-- Password -->
        <input-textual
        label="Password"
        type="password"
        name="password"
        validation-message="{{ $errors->first('password') }}"></input-textual>

        <!-- Password Confirmation -->
        <input-textual
        label="Confirm Password"
        type="password"
        name="password_confirmation"
        validation-message="{{ $errors->first('password_confirmation') }}"></input-textual>

        <!-- Password Confirmation -->
        @if($user->uses_two_factor_auth)
        <input-textual
        label="Authentication Token"
        type="text"
        name="two_factor_token"
        :disable-default-old="true"
        validation-message="{{ $errors->first('two_factor_token') }}"></input-textual>
        @endif
    </fe-form>

    <ul class="auth-page-navigation-menu list-unstyled">
        <li>
            <a href="/password/reset/?email={{ $email or old('email') }}" class="fc-color5">Start Over?</a>
        </li>
        <li>
            <a href="/login">Login</a>
        </li>
        <li>
            <a href="/register">Register</a>
        </li>
    </ul>
</div>
@endsection
