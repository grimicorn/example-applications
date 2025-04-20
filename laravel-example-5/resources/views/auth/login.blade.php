@extends('layouts.auth')

@section('content')
    <ajax-form
        data-id="login"
        data-method="POST"
        data-action="{{ route('login') }}"
        data-submit-label="Login"
        data-submit-alignment="full"
    >
        <template slot="inputs">

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

            <input-checkbox
                data-label="Remember Me"
                data-name="remember_me"
            ></input-checkbox>
        </template>

        <template slot="after-submit">
            <div class="flex justify-center pt-6">
                <a
                    class="no-underline hover:underline text-brand-dark text-sm"
                    href="{{ route('password.request') }}"
                >
                    Forgot Your Password?
                </a>
            </div>
        </template>
    </ajax-form>
@endsection
