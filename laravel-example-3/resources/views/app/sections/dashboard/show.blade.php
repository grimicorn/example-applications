@extends('layouts.application')

@section('content')
@include('app/sections/dashboard/partials/dashboard-alert')

<div class="row">
    <div class="col-xs-6" id="overlay_tour_step_3">
        <app-notification-accordion
        class="notification-messages-dashboard"
        data-all-url="{{ route('notifications.index') }}"
        :data-notifications="{{ json_encode($notifications->items()) }}"></app-notification-accordion>
    </div>
    <div class="col-xs-6" id="overlay_tour_step_4">
        <app-form-accordion
        header-title="Exchange Spaces"
        class="dashboard-exchange-spaces"
        :collapsible="false">
            <div slot="content" class="row">
                @include('app/sections/dashboard/partials/exchangespace-list')
            </div>
        </app-form-accordion>
        <div class="text-center pa3 br1 bb1 bl1">
            <a
            class="fc-color7"
            href="{{ route('exchange-spaces.index') }}">See All Exchange Spaces</a>
        </div>
    </div>
</div>
@endsection
