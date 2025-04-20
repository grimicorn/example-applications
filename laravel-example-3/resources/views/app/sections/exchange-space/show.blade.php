@extends('layouts.application')

@if(!$space->buyerHasLeft())
    @section('before-content')
    @component('app.sections.shared.action-bar', ['class' => 'bb2'])
        @slot('left')
        @endslot

        @slot('right')
            @if($space->currentMember->is_seller)
            <app-exchange-space-remove-member
            route="{{
                route('exchange-spaces.member.destroy', [
                    'id' => $space->id,
                    'm_id' => null,
                ])
            }}"
            load-route="{{ route('exchange-spaces.member.index', ['id' => $space->id]) }}"></app-exchange-space-remove-member>

            <app-exchange-space-new-member
            :is-request="false"
            route="{{ route('exchange-spaces.member.store', ['id' => $space->id]) }}"
            search-route="{{ route('exchange-spaces.member.index', ['id' => $space->id]) }}"
            load-search="{{ $loadSearch }}"
            :data-load-user-id="{{ $loadUserId }}"
            deny-route="{{ $denyRoute }}"></app-exchange-space-new-member>

            @if(!$space->listing->exitSurvey and $space->deal_complete)
                <app-listing-exit-survey-modal
                :data-use-initial-close-content="{{ (null !== session('stage_advanced_to')) ? 'true' : 'false'}}"
                :data-disable-space-close="true"
                :data-auto-open="{{ (null !== session('stage_advanced_to')) ? 'true' : 'false'}}"
                :data-business-sold="true"
                :data-is-deal-close="true"
                data-button-label="Complete Survey"
                :data-exit-survey="{{ json_encode($space->listing->exitSurvey) }}"
                data-submit-route="{{
                    route('listing.exit-survey.store', ['id' => $space->listing->id])
                }}"
                :data-use-button-link="false"></app-listing-exit-survey-modal>
            @endif

            @if($space->deal_complete)
            <app-listing-exit-survey-modal
            :data-business-sold="true"
            :data-exit-survey="{{ json_encode($space->listing->exitSurvey) }}"
            data-submit-route="{{
                route('listing.destroy', ['id' => $space->listing->id, 'space_id' => $space->id])
            }}"
            :data-use-button-link="false"
            :data-is-space-close="true"></app-listing-exit-survey-modal>
            @else
            <fe-form
            form-id="space-advance-stage"
            action="{{ route('exchange-spaces.advance-stage.update', ['id' => $space->id]) }}"
            method="PATCH"
            submit-label="Advance Stage"
            confirm-message='<p>Ready to advance your deal to the next milestone? Click the "Advance Stage" button below to move the deal into the next stage. This helps you and everyone else in the Exchange Space keep track of progress.</p> <p>All parties in the Exchange Space will be notified of this change. It is best practice to get sign-off from the buyer before processing this update. Please note that this action cannot be undone.</p>'
            confirm-title="Advance Stage"
            confirm-submit-label="Advance Stage"
            :submit-input-height="false"></fe-form>
            @endif
            @else
            <app-exchange-space-new-member
            :is-request="true"
            route="{{ route('exchange-spaces.member.store', ['id' => $space->id]) }}"
            search-route="{{ route('exchange-spaces.member.index', ['id' => $space->id]) }}"
            load-search="{{ $loadSearch }}"></app-exchange-space-new-member>
            @endif
        @endslot
    @endcomponent
    @endsection
@endif

@section('content')

@if($space->buyerHasLeft())
    @include('app.sections.exchange-space.partials.buyer-left')
@endif

@if($space->deal_complete)
    @include('app.sections.exchange-space.partials.congratulations-message')
    @include('app.sections.exchange-space.partials.show-stages')
@else
    @include('app.sections.exchange-space.partials.show-stages')
@endif


@include('app.sections.exchange-space.partials.show-members')

<app-notification-accordion
        {{--class="notification-messages-dashboard"--}}
        data-all-url="{{ route('exchange-spaces.notifications.index', ['id' => $space->id]) }}"
        :data-notifications="{{ json_encode($notifications) }}"></app-notification-accordion>
<div class="disclaimer">
    Disclaimer: This information is provided as a tool and reference point for all parties of the transaction to help keep deals on track. This information may not be suitable for all transactions and should not be construed as advice. Please see our <a href="/terms-conditions">Terms and Conditions</a> for more information.
</div>

@include('app.sections.exchange-space.partials.footer')

@endsection
