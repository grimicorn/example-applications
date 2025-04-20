@extends('layouts.application')

@section('before-content')
    @include('app/sections/shared/exchange-space/message-action-bar')
@endsection

@section('content')
@include('app/sections/shared/exchange-space/messages', [
    'title' => 'Business Inquiry',
])
@include('app/sections/shared/exchange-space/message-input')

@if(optional($space->current_member)->is_seller and $space->is_inquiry)
<app-exchange-space-inquiry-welcome
:display="{{ $currentUser->display_inquiry_intro ? 'true' : 'false' }}"
route="{{ route('business-inquiry.display-intro.destroy') }}"></app-exchange-space-inquiry-welcome>
@endif
@endsection
