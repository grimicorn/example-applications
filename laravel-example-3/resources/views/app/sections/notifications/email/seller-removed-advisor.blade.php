@if($recipient_member_removed ?? false)
You have been removed from an Exchange Space by the seller.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}

If you believe this was in error or have any questions about this removal, we encourage you to reach out to the seller directly.
@else
A member has been removed from an Exchange Space to which you belong.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Removed Member:** <a href="{{ $removed_member_profile_url }}">{{ $removed_member_name }}</a><br>

Click the button below to go directly to the Exchange Space.  If you have any questions about this removal, we encourage you to speak immediately with the seller who controls the Exchange Space.

@component('mail::button', [
    'url' => $exchange_space_url,
])
    Go to Exchange Space
@endcomponent
@endif
