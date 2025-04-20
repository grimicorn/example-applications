@extends('layouts.application')

@section('before-content')
    @component('app.sections.shared.action-bar')
        @slot('full')
            <app-exchange-space-conversation-filters
            :sort-options="{{ json_encode($sortOptions) }}"
            :is-inquiry="true"
            route="{{ route('business-inquiry.index') }}"></app-exchange-space-conversation-filters>
        @endslot
    @endcomponent
@endsection

@section('content')
    <app-form-accordion
    header-title="Business Inquiries"
    :collapsible="false"
    :disable-padding="true"
    class="bb0 mb0">
        <template slot="content">
            <app-exchange-space-conversation-filter
            :paginated-conversations="{{ json_encode($conversations) }}"
            :inquiry="true"
            route="{{ route('business-inquiry.index', array_filter([
                'all' => request()->get('all'),
            ])) }}"></app-exchange-space-conversation-filter>
        </template>

    </app-form-accordion>
@endsection
