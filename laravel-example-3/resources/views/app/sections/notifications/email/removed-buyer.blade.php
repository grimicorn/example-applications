@if($is_seller)
The Exchange Space "{{ $exchange_space_title }}," which you control, has been closed for all other parties because the buyer decided to leave. As the seller, you alone retain access until you are ready to delete it. The buyer was warned that leaving would close the Exchange Space for all parties and that the action could not be undone.

Click the button below to go to the Exchange Space in question where you can download any conversations or documents that you wish to keep.

@component('mail::button', [
    'url' => $exchange_space_url,
])
Go to Exchange Space
@endcomponent
@else
The Exchange Space "{{ $exchange_space_title }}" of which you were a member has been closed because the buyer decided to leave it. The buyer was warned that leaving the Exchange Space would close it for all parties and it could not be undone.
@endif
