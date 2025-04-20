@if($is_default_message)
The seller was given an opportunity to provide a message to be shared with the members of the Exchange Space but declined to do so.
@else
Additionally, the seller asked us to share with you the following:

@include('app.sections.notifications.email.partials.user-message', [
    'message' => $message,
    'report_abuse_link' => $report_abuse_link,
])
@endif
