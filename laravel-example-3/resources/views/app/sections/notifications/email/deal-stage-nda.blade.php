The seller of an Exchange Space of which you are a member has advanced the deal stage.  The details are as follows:

**Business title:** {{ $listing_title }}<br>
**Exchange Space:** {{ $exchange_space_title}}<br>
**Previous deal stage:** Getting Started<br>
**Current deal stage:** Due Diligence

With preliminary questions addressed and both parties ready to move forward, the process advances to one of the more time-consuming and critical phases of the process: Due Diligence. Buyers are encouraged to ask questions by starting new conversations in the Diligence Center. At this point, sellers may decide to share more detailed information about the business, including providing access to their Historical Financials. The buyer and seller are encouraged to develop a process for submitting questions and agree on a timeline to help keep the deal on track.

Click the button below to go directly to the Exchange Space.  If you have any questions about the advancement, we encourage you to speak with the seller who controls the Exchange Space immediately.


@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
