@extends('layouts.application')

@section('before-content')
    @component('app.sections.shared.action-bar')
        @slot('left')
            @if($space->current_member->is_seller)
                <app-exchange-space-toggle-access
                        name="adjusted_financials_trends_access"
                        label="Access to Historical Financials & Trends"
                        tooltip="Toggle to allow the buyer to access the historical financials and adjusted financials & trends."
                        route="{{ route('exchange-spaces.adjusted-financials-trends.update', ['id' => $space->id]) }}"
                        :value="{{
                        $space->historical_financials_public ? 'true' : 'false'
                    }}"></app-exchange-space-toggle-access>
            @endif
        @endslot
        @slot('right')
            <print-page></print-page>
            <app-sort-table-export-button
                    route="{{
        route('exchange-spaces.adjusted-financials-trends.csv.index', [
            'id' => $space->id,
        ])
    }}"
                    :filters="{}"></app-sort-table-export-button>
        @endslot

        @slot('info')
            @if($space->current_member->is_seller)
                <span class="info">Financial Data is managed at the business level so that you don't have to update the same information in multiple locations. <a href="{{ route('listing.historical-financials.edit', ['id' => $space->listing->id])}}" class="fc-color4">Click here</a> to add to or modify your business's Historical Financials.</span>
            @endif
        @endslot

    @endcomponent
@endsection

@section('content')
    @include('app.sections.exchange-space.adjusted-financials-trends.table')
    <div class="disclaimer">
        Disclaimer: The information for this business has been provided by the Seller and/or his or her designee and has not been verified by The Firm Exchange LLC. The Firm Exchange LLC does not own a stake in the business or in its sale, and as such, makes no claims or guarantees as to the accuracy or completeness of the information provided. Please see our <a href="/terms-conditions">Terms and Conditions</a> for more information.
    </div>
@endsection
