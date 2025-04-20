A member of the Firm Exchange community has been added to an Exchange Space of which you are a member.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Member added:** <a href="{{ $requested_member_profile_url }}">{{ $requested_member_name }}</a><br>

Click the button below to go to the Exchange Space in question.  If you have any questions or concerns about the addition, we encourage you to speak immediately with the Seller who controls the Exchange Space.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
