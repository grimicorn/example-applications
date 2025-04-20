@extends('layouts.application')

@section('content')
<div class="profile-settings-wrap">
    @include('app.sections.profile.settings.update-password')
    @include('app.sections.profile.settings.account-security')
    @include('app.sections.profile.settings.login-notification')
    <hr style="width: 100%; height: 3px; background-color: #D77F34; color: #D77F34; border: 0 none; margin-top: 5px; margin-bottom: 20px">
    @include('app.sections.profile.settings.close-account')
</div>
@endsection
