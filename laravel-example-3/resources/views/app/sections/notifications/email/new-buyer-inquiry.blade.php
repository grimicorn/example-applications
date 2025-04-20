You have received an inquiry regarding one of your businesses on FirmExchange.com.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Potential buyer:** <a href="{{ $buyer_profile_url }}">{{ $buyer_first_name }} {{ $buyer_last_name }}</a><br>
**Message:**<br>
@include('app.sections.notifications.email.partials.user-message', [
    'message' => $message,
    'report_abuse_link' => $report_abuse_link,
])

To act on this message, please click the button below.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Business Inquiry
@endcomponent
