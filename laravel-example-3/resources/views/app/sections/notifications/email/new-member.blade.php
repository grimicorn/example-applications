{{ $requester_user_name }} has requested a new member be added to an Exchange Space you control.  Please find the details below:

Business: {{ $listing_title }}

Exchange Space: {{ $exchange_space_title }}

User: <a href="{{ $profile_link }}" target="_blank">{{ $user_name }}</a>

Occupation: {{ $user_occupation }}

To view more information about this member, and to take action on the request, click the button below.

@component('mail::button', [
    'url' => $exchange_space_url,
])
View Member Request
@endcomponent

