@extends('layouts.marketing')
@section('content')
<div class="container auth-form-wrap">
    <h1 class="text-center auth-page-title">{{ $pageTitle }}</h1>

    <fe-form
    action="/login"
    form-id="login-form"
    class="login-form auth-form"
    submit-label="Login"
    :disabled-unload="true"
    :submit-centered="true"
    :submit-ignore-errors="true"
    data-error-alert-valiation-message="{{ $errorAlertValidationMessage }}">
        {{-- E-Mail Address --}}
        <input-textual
        type="email"
        name="email"
        value="{{ old('email') }}"
        validation-message="{{ $errors->first('email') }}"
        label="E-Mail Address"
        validation="required"
        autofocus="autofocus"></input-textual>

        {{-- Password --}}
        <input-textual
        type="password"
        name="password"
        value="{{ old('password') }}"
        validation-message="{{ $errors->first('password') }}"
        label="Password"
        validation="required"></input-textual>

        {{-- Remember Me --}}
        <input-checkbox
        name="remember"
        value="{{ old('remember') }}"
        validation-message="{{ $errors->first('remember') }}"
        label="Remember Me"
        :data-disable-off="true"></input-checkbox>
    </fe-form>

    <nav class="auth-page-navigation">
        <ul class="auth-page-navigation-menu list-unstyled">
            <li>
                <a
                href="/password/reset/"
                class="fc-color5">Forgot Password?</a>
            </li>
            <li>
                <a href="{{ route('register') }}">Register</a>
            </li>
        </ul>
    </nav>
</div>
@endsection
