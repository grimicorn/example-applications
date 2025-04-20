@php
use App\Support\Styleguide\Emails;

$emails = (new Emails);
$models = $emails->models();
$user = $models->get('user');
$listing = $models->get('listing');
$professional = $models->get('user');
$space = $models->get('space');
$conversation = $models->get('conversation');
$search = $models->get('search');
$extraData = $emails->extraData();
$notifications = $emails->getNotifications();
@endphp

<form action="?" method="GET">
    <label>
        User ID: <input type="number" name="user_id" value="{{ $user->id }}">
    </label><br>
    <label>
        Business ID: <input type="number" name="listing_id" value="{{ $listing->id }}">
    </label><br>
    <label>
        Space ID: <input type="number" name="space_id" value="{{ $space->id }}">
    </label><br>
    <label>
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
    <label>
        Conversation ID: <input type="number" name="conversation_id" value="{{ $conversation->id }}">
    </label><br>
    <label>
        Saved Search ID: <input type="number" name="saved_search_id" value="{{ $search->id }}">
    </label><br>
    <label>
        Recipient User ID: <input type="number" name="recipient_user_id" value="{{ $extraData->get('recipient_user_id') }}">
    </label><br>
    <label>
        Requested User ID: <input type="number" name="requested_user_id" value="{{ $extraData->get('requested_user_id') }}">
    </label><br>
    <label>
        Removed User ID: <input type="number" name="removed_user_id" value="{{ $extraData->get('removed_user_id') }}">
    </label><br>
    <label>
        Requester User ID: <input type="number" name="requester_user_id" value="{{ $extraData->get('requester_user_id') }}">
    </label><br>
    <label>
        Close Message: <textarea name="close_message">{{ $extraData->get('close_message') }}</textarea>
    </label><br>
    <label>
        Exit Message: <textarea name="exit_message">{{ $extraData->get('exit_message') }}</textarea>
    </label><br>
    <label>
        Explanation: <textarea name="explanation">{{ $extraData->get('explanation') }}</textarea>
    </label><br>
    <label>
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
    </label><br>

    <button type="submit">Update</button>
</form>

<h1>Mailable List</h1>
<a
href="{{ route('styleguide.show.notifications') }}">
View Dashboard Examples Notifications Here
</a>
<ul>
    <li>
        <a
        href="{{ route('mailable.conversation-abuse-reported', $extraData->toArray()) }}"
        target="_blank">
            Conversation Abuse Reported
        </a>
    </li>

    <li>
        <a
        href="{{ route('mailable.abuse-report', $extraData->toArray()) }}"
        target="_blank">
            Abuse Reported
        </a>
    </li>

    <li>
        <a
        href="{{ route('mailable.marketing-contact-received', $extraData->toArray()) }}"
        target="_blank">
            Marketing Contact Received
        </a>
    </li>

    @foreach ($notifications as $notification)
        <li>
            <a href="{{ $notification['route'] }}" target="_blank">{{ $notification['title'] }}</a>

            @if (!file_exists(resource_path($notification['view_path'])))
                <br><strong>File ({{ $notification['file_path'] }}.blade.php) Not Found</strong>
            @endif
        </li>
    @endforeach
</ul>
