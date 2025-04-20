{{ $sender_name }} has sent you a message through the Firm Exchange platform. If you wish to respond to the message, please use the user's contact information provided below. Please note that we do not monitor user's correspondence.

**Name:** {{ $sender_name }}<br>
**Phone:** {{ $sender_phone }}<br>
**Email:** <a href="mailto:{{ $sender_email }}">{{ $sender_email }}</a><br>
**Message:**<br>
@include('app.sections.notifications.email.partials.user-message', [
    'message' => $message,
    'report_abuse_link' => $report_abuse_link,
])
