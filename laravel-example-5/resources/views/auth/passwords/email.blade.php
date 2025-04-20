@extends('layouts.auth')

@section('content')
    <ajax-form
        data-id="reset_password"
        data-method="POST"
        data-action="{{ route('password.email') }}"
        data-submit-label="Send Password Reset Link"
        data-submit-alignment="full"
    >
        <template slot="inputs">
            <input-text
                data-label="E-Mail Address"
                data-id="email"
                data-type="email"
                data-name="email"
                :data-autofocus="true"
            ></input-text>
        </template>
    </ajax-form>
@endsection
