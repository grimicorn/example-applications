Regrettably, your inquiry into "{{ $listing_title }}" has been rejected.  The seller indicated that the cause for this rejection was:

@if($use_listing_closed)
<i>Business Removed</i>
@else
<i>{{ $rejected_reason }}</i>
@endif

@if(!$is_default_message)
In addition to the above rationale, the seller asked us to share with you the following:

@include('app.sections.notifications.email.partials.user-message', [
    'message' => $message,
    'report_abuse_link' => $report_abuse_link,
])
@endif

If you believe this was an error, we encourage you to reach out to the seller.

Please let us know if there’s any way we can help.


