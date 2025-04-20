@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
		'pageTitle' => 'Styleguide',
		'pageSubtitle' => 'Notifications',
])
@endsection

@section('styleguide-content')
    <form action="?" method="GET" class="mb-3">
        <label>
            User ID:
            <input type="text" name="user_id" value="{{ $notification_ids->get('user_id') }}">
        </label><br>
        <label>
            Saved Search ID:
            <input type="text" name="saved_search_id" value="{{ $notification_ids->get('saved_search_id') }}">
        </label><br>
        <label>
            Exchange Space ID:
            <input type="text" name="space_id" value="{{ $notification_ids->get('space_id') }}">
        </label><br>
        <label>
            Conversation ID:
            <input type="text" name="conversation_id" value="{{ $notification_ids->get('conversation_id') }}">
        </label><br>
        <label>
            Requested Member User ID:
            <input type="text" name="requested_user_id" value="{{ $notification_ids->get('requested_user_id') }}">
        </label><br>
        <label>
            Removed Member User ID:
            <input type="text" name="removed_user_id" value="{{ $notification_ids->get('removed_user_id') }}">
        </label><br>
        <label>
            Conversation Member User ID:
            <input type="text" name="conversation_member_user_id" value="{{ $notification_ids->get('conversation_member_user_id') }}">
        </label><br>

        <label class="mb2 inline-block">
            Recipient ID:
            <input type="text" name="recipient_user_id" value="{{ $notification_ids->get('recipient_user_id') }}">
        </label><br>

        <button type="submit">Submit</button>
    </form>

    <br><br><br>

    <h3>Notifications To Test</h3>

    @foreach ($notification_labels as $key => $label)
        @if($notifications->where('type', $key)->isEmpty()
        and $key !== \App\Support\Notification\NotificationType::LOGIN
        and $key !== \App\Support\Notification\NotificationType::NEW_USER
        and $key !== \App\Support\Notification\NotificationType::RESET_PASSWORD
        and $key !== \App\Support\Notification\NotificationType::CLOSED_ACCOUNT)
        [ ] {{ $label }}<br>
        @else
        [x] {{ $label }}<br>
        @endif
    @endforeach
    <br>
    <a
    href="{{ route('mailable.list') }}">
    View Email Example Notifications Here
    </a>
    <br><br></br>

    <h3>Notification - Show All</h3>
    <app-notification-accordion
    :data-show-all="true"
    data-all-url="{{ route('notifications.index') }}"
    :data-notifications="{{ json_encode($notifications) }}"></app-notification-accordion>

    <br><br><br>

    <h3>Notification - Show All Toggle</h3>
    <app-notification-accordion
    :data-notifications="{{ json_encode($notifications) }}"></app-notification-accordion>

    <br><br><br>

    <h3>Notification - Show All Link</h3>
    <app-notification-accordion
    data-all-url="{{ route('notifications.index') }}"
    :data-notifications="{{ json_encode($notifications) }}"></app-notification-accordion>

    <br><br><br>

    <h3>Notification - None</h3>
    <app-notification-accordion
    data-all-url="{{ route('notifications.index') }}"
    :data-notifications="{{ json_encode([]) }}"></app-notification-accordion>

@endsection
