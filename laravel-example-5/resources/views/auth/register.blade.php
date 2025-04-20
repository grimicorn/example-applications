@extends('layouts.auth')

@section('content')
    <ajax-form
        data-id="register"
        data-method="POST"
        data-action="{{ route('register') }}"
        data-submit-label="Register"
        data-submit-alignment="full"
    >
        <template slot="inputs">
            <input-text
                data-label="First Name"
                data-name="first_name"
                data-placeholder="First"
                :data-autofocus="true"
                :data-required="true"
            ></input-text>

            <input-text
                data-label="Last Name"
                data-name="last_name"
                data-placeholder="Last"
                :data-required="true"
            ></input-text>

            <input-text
                data-type="email"
                data-label="Email"
                data-name="email"
                data-placeholder="you@domain.com"
                :data-required="true"
            ></input-text>

            <input-text
                data-type="password"
                data-label="Password"
                data-name="password"
                data-placeholder="my$uperC0mplexPa22word"
                :data-required="true"
            ></input-text>

            <input-text
                data-type="password"
                data-label="Confirm Password"
                data-name="password_confirmation"
                data-placeholder="my$uperC0mplexPa22word"
                :data-required="true"
            ></input-text>
        </template>
    </ajax-form>
@endsection
