The seller of an Exchange Space of which you are a member has advanced the deal stage.  The details are as follows:

**Business title:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>
**Previous deal stage:** {{ $previous_deal_label }}<br>
**Current deal stage:** {{ $deal_label }}

@if($original_space->deal_loi_signed)
The buyer has conducted sufficient analysis and review of the business that he or she has decided to make an offer to buy the business. This may be done with a Letter of Intent outlining the final purchase price, terms and conditions, any contingencies (such as financing), and any final work to be completed. In some cases, earnest money is also provided, which demonstrates buyer commitment and helps compensate the seller for closing arrangements if the deal falls through.
@elseif($original_space->deal_complete)
Congratulations! The parties have likely agreed to terms and may have signed a purchase agreement. This final stage is focused on completing the sale of the business in question. Once the deal has been officially completed with all assets transferred to the buyer and payment received by the seller, the seller should return and close out the Exchange Space and the business listing using the “Close Listing” button available on their Exchange Space Home page.
@endif

Click the button below to go directly to the Exchange Space.  If you have any questions about the advancement, we encourage you to speak with the seller who controls the Exchange Space immediately.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
