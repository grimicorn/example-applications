We are pleased to inform you that {{ $seller_name }} has accepted your inquiry into "{{ $listing_title }}"!  This is an exciting first step toward buying your new business.

An Exchange Space has been created between you and {{ $seller_first_name}} where you can continue the conversation.  There you will find tools to help conduct due diligence, share documents, and more.

At this time, this conversation has been placed in read-only mode but will be available for reference.  The inquiry will always be viewable from the Business Inquiry section and a copy has been placed in the newly created Exchange Space as well.

View the inquiry by clicking the button blow.  From there, you can also access the Exchange Space.  Please let us know if we can be of any assistance.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Business Inquiry
@endcomponent

