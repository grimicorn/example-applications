@extends('layouts.marketing')
@section('content')

    @if(config('app.disable_registration_form'))
        @include('marketing.partials.auth.mailchimp-sign-up')
    @else
    <div class="container auth-form-wrap">
        @include('marketing.partials.auth.register-form')
    </div>
    @endif
@endsection
