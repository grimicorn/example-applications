@if($use_listing_closed)
A business you were a party to, "{{ $listing_title }}," has been deleted by the seller. This action in turn closed all outstanding Exchange Spaces and Business Inquiries related to that listing.  The seller was warned that the action was permanent and should not be taken lightly.
@else
An Exchange Space of which you are a member was deleted by the seller.  The seller was warned that the action was permanent and should not be taken lightly.  The details are as follows:

**Business title:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Seller:** {{ $seller_name }}<br>
@endif

@include('app.sections.notifications.email.partials.close-message', [
    'message' => $message,
    'report_abuse_link' => $report_abuse_link,
])
