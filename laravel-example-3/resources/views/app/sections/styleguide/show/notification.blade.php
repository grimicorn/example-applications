@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
		'pageTitle' => 'Styleguide',
		'pageSubtitle' => 'Notifications',
])
@endsection

@php
$emails = (new \App\Support\Styleguide\Emails);
$emailNotification = $emails->getNotification($notificationType = request()->get('type'));
$extraData = $emails->extraData();
$models = $emails->models();
$space = optional($models->get('space'));
@endphp

@section('styleguide-content')
    <form action="?" method="GET" class="mb3">
        <label class="mb2 inline-block">
            Type:
            <select name="type" id="type">
                <option value="0">Select a type</option>
                @foreach($notification_labels as $type => $label)
                    <option
                    {{ intval($type) === $notification_type ? 'selected' : '' }}
                    value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
        </label><br>

        <label class="mb2 inline-block">
            User ID:
            <input type="text" name="user_id" value="{{ $notification_ids->get('user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Business ID:
            <input type="text" name="listing_id" value="{{ $notification_ids->get('listing_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Exchange Space ID:
            <input type="text" name="space_id" value="{{ $notification_ids->get('space_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Exchange Space Deal:
            <select name="deal">
                <option value="">Select Status</option>
                @foreach(\App\Support\ExchangeSpaceDealType::getLabels() as $id => $label)
                    <option
                        value="{{ $id }}"
                        {{ (intval($id) === intval($extraData->get('deal'))) ? 'selected' : '' }}
                    >{{ $label}}</option>
                @endforeach
            </select><br>
            <i><small>Defaults to selected Exchange Space Status</small></i>
        </label><br>

        <label class="mb2 inline-block">
            Conversation ID:
            <input type="text" name="conversation_id" value="{{ $notification_ids->get('conversation_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Saved Search ID:
            <input type="text" name="saved_search_id" value="{{ $notification_ids->get('saved_search_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Recipient User ID:
            <input type="text" name="recipient_user_id" value="{{ $notification_ids->get('recipient_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Requested User ID:
            <input type="text" name="requested_user_id" value="{{ $notification_ids->get('requested_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Removed User ID:
            <input type="text" name="removed_user_id" value="{{ $notification_ids->get('removed_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Requester User ID: <input type="number" name="requester_user_id" value="{{ $notification_ids->get('requester_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Conversation Member User ID:
            <input type="text" name="conversation_member_user_id" value="{{ $notification_ids->get('conversation_member_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Close Message: <textarea name="close_message">{{ $extraData->get('close_message') }}</textarea>
        </label><br>

        <label class="mb2 inline-block">
            Exit Message: <textarea name="exit_message">{{ $extraData->get('exit_message') }}</textarea>
        </label><br>

        <label class="mb2 inline-block">
            Explanation: <textarea name="explanation">{{ $extraData->get('explanation') }}</textarea>
        </label><br>

        <label class="mb2 inline-block">
        Reason:
        <select name="reason">
            <option value="">Select a Reason</option>
            <option value="Insufficient information in profile"
            {{ $extraData->get('reason') === 'Insufficient information in profile' ? 'selected' : '' }}>Insufficient information in profile</option>
            <option value="Mismatch based on profile"
            {{ $extraData->get('reason') === 'Mismatch based on profile' ? 'selected' : '' }}>Mismatch based on profile</option>
            <option value="Business Removed"
            {{ $extraData->get('reason') === 'Business Removed' ? 'selected' : '' }}>Business Removed</option>
            <option value="Requesting additional information before proceeding"
            {{ $extraData->get('reason') === 'Requesting additional information before proceeding' ? 'selected' : '' }}>Requesting additional information before proceeding</option>
            <option value="Other"
            {{ $extraData->get('reason') === 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </label><br><br>

        <button type="submit">Submit</button>
        <input type="reset" value="Reset" class="btn btn-color6">
    </form>

    @if($emailRoute = $emailNotification['route'] ?? '')
        <a
            href="{{ $emailRoute }}"
            target="_blank"
            class="bold mb3 btn btn-color5"
        >Matching Email</a><br>
    @endif

    @if(intval($notificationType) === \App\Support\Notification\NotificationType::DELETED_EXCHANGE_SPACE)
        <a
            href="{{ $space->closedUrl() }}"
            target="_blank"
            class="bold mb3 btn btn-color5"
        >Closed Page</a><br>
    @endif

    @isset($notification)
        <app-notification-accordion
        :data-show-all="true"
        data-all-url="{{ route('notifications.index') }}"
        :data-notifications="{{ json_encode([$notification]) }}"></app-notification-accordion>
    @endisset
@endsection
