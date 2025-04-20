@extends('layouts.marketing')
@section('content')
<div class="container auth-form-wrap">
    @if(session('status'))
    <alert type="success">
        {{ session('status') }}
    </alert>
    @endif

    <h1 class="text-center auth-page-title">Forgot Password?<br>Request a New One:</h1>

    <fe-form
    action="/password/email"
    form-id="reset-password-form"
    class="reset-password-form auth-form"
    submit-label="Submit"
    :submit-centered="true"
    :submit-ignore-errors="true">
        {{-- E-Mail Address --}}
        <input-textual
        type="email"
        name="email"
        value="{{ old('email', request()->get('email')) }}"
        validation-message="{{ $errors->first('email') }}"
        label="E-Mail Address"
        validation="required"
        autofocus="autofocus"></input-textual>
    </fe-form>

    <nav class="auth-page-navigation">
        <ul class="auth-page-navigation-menu list-unstyled">
            <li>
                <a
                href="/login"
                class="fc-color5">Login</a>
            </li>
        </ul>
    </nav>
</div>
@endsection
