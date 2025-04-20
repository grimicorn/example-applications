@extends('layouts.marketing')

@section('content')
<div class="container auth-form-wrap">
    <h1 class="text-center auth-page-title">{{ $pageTitle }}</h1>

    <fe-form
    action="/login/token"
    form-id="login-token-form"
    class="login-token-form auth-form"
    submit-label="Verify"
    method="POST"
    :submit-centered="true">
        <!-- Token -->
        <input-textual
        name="token"
        label="Authentication Token"></input-textual>
    </fe-form>

    <nav class="auth-page-navigation">
        <ul class="auth-page-navigation-menu list-unstyled">
            <li>
                <a
                href="{{ url('login-via-emergency-token') }}"
                class="fc-color5"
                dusk="lost-your-device-link">Lost Your Device?</a>
            </li>
        </ul>
    </nav>
</div>

@endsection
