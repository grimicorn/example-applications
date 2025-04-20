@extends('layouts.auth')

@section('content')
    <ajax-form
        data-id="reset_password"
        data-submit-label="Reset Password"
        data-method="POST"
        data-action="{{ route('password.request') }}"
        data-submit-alignment="full"
    >
        <template slot="inputs">
            <input type="hidden" name="token" value="{{ $token }}">

            <input-text
                data-label="E-Mail Address"
                data-id="email"
                data-type="email"
                data-name="email"
                data-value="{{ $email ?? old('email') }}"
                :data-required="true"
                :data-autofocus="true"
            ></input-text>

            <input-text
                data-label="Password"
                data-id="password"
                data-type="password"
                data-name="password"
                :data-required="true"
            ></input-text>

            <input-text
                data-label="Confirm Password"
                data-id="password_confirmation"
                data-type="password"
                data-name="password_confirmation"
                :data-required="true"
            ></input-text>
        </template>
    </ajax-form>
@endsection
