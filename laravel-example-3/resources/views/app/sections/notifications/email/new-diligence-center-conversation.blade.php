A new Diligence Center conversation has been created in an Exchange Space of which you are a member.  The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Conversation title:** {{ $conversation_title }}<br>
**Submitted by:** <a href="{{ $sender_profile_url }}">{{ $sender_name }}</a><br>
**Message:**<br>
@include('app.sections.notifications.email.partials.user-message', [
    'message' => $message_body,
    'report_abuse_link' => $report_abuse_link,
])

To respond to the message, click on the button below to visit the Diligence Center.

@component('mail::button', [
    'url' => $conversation_url,
])
View Diligence Center Conversation
@endcomponent
