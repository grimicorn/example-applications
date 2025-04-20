@extends('layouts.application')

@section('before-content')
@component('app.sections.shared.action-bar')
@slot('left')
<app-exchange-space-conversation-filters
:category-options="{{ json_encode($categoryOptions) }}"
:status-options="{{ json_encode($statusOptions) }}"></app-exchange-space-conversation-filters>
@endslot
@slot('right')
    <a
    href="{{ route('exchange-spaces.conversations.create', [
        'id' => $space->id,
    ]) }}"
    class="btn fe-input-height">New Conversation</a>
@endslot
@endcomponent
@endsection

@section('content')
<app-form-accordion
header-title="Conversations"
:collapsible="false"
:disable-padding="true"
class="mb3">
    <template slot="header-right">
        <app-exchange-space-unresolved-conversation-count
        :count="{{ $unresolvedCount }}"></app-exchange-space-unresolved-conversation-count>
    </template>

    <template slot="content">
        <app-exchange-space-conversation-filter
        :paginated-conversations="{{ json_encode($conversations) }}"
        route="{{ route('exchange-spaces.conversations.index', array_filter([
            'id' => $space->id,
            'all' => request()->get('all'),
        ])) }}"></app-exchange-space-conversation-filter>
        <div class="flex pt3 pb3 pl1 pr1 justify-center">
            <a
            class="fc-color7 a-ul"
            href="{{ route('exchange-spaces.conversations.index', [
                'id' => $space->id,
                'all' => 1,
            ]) }}">See All Messages</a>
        </div>
    </template>
</app-form-accordion>

<app-exchange-space-documents-list
id="overlay_tour_documents_step"
:data-documents="{{ json_encode($documents) }}"
delete-url="{{ route(
    'exchange-spaces.file.destroy',
    ['id' => $space->id]
) }}"></app-exchange-space-documents-list>
@endsection
