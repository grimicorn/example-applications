A member of an Exchange Space you control has requested that someone be added as an advisor.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Member to be added:** <a href="{{ $requested_member_profile_url }}">{{ $requested_member_name }}</a><br>
**Requested by:** <a href="{{ $requester_profile_url }}">{{ $requester_user_name }}</a><br>

Click the button below to go to the Exchange Space in question and act on this request.  If you have any questions or concerns about the addition, we encourage you to speak immediately with the requester.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
