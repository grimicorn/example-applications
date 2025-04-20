@extends('layouts.application')

@section('content')

@include('app.sections.profile.partials.form-actions')

<div class="profile-payments-wrap">
    @if(!$currentUser->transactions->isEmpty() or $currentUser->card_last_four or $currentUser->isSubscribed())
    @include('app.sections.profile.payments.payment-method', [
        'update_form' => true,
    ])
    @endif
    @include('app.sections.profile.payments.billing-status')
    @include('app.sections.profile.payments.transaction-history')
</div>
@endsection
