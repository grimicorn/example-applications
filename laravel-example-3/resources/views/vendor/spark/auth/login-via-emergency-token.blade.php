@extends('layouts.marketing')

@section('content')
<div class="container auth-form-wrap">
    <h1 class="text-center auth-page-title">{{ $pageTitle }}</h1>
    <!-- Warning Message -->
    <alert type="warning" :dismissible="false">
        After logging in via your emergency token, two-factor authentication will be
        disabled for your account. If you would like to maintain two-factor
        authentication security, you should re-enable it after logging in.
    </alert>

    <fe-form
    action="/login-via-emergency-token"
    form-id="login-via-emergency-token"
    class="login-via-emergency-token auth-form"
    submit-label="Login"
    method="POST"
    :submit-centered="true"
    data-error-alert-valiation-message="{{ $errorAlertValidationMessage }}">
        <!-- Emergency Token -->
        <input-textual
        name="token"
        type="password"
        data-disable-error-message-cleanup="true"
        label="Emergency Token"></input-textual>
    </fe-form>
</div>
@endsection
