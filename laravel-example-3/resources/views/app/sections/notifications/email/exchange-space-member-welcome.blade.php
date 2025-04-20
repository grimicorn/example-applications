You have been added to a new Exchange Space on FirmExchange.com. The details are as follows:

**Business:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title }}<br>

Click the button below to go to the Exchange Space in question.  If you have any questions or concerns about the addition, we encourage you to immediately speak with the Seller who controls the Exchange Space.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
