Thank you for being a part of the Firm Exchange community - we're sorry to see you go. We've closed your account as you asked. If you would like to rejoin us, you may do so at any time by going to <a href=”{{ $register_url }}”>{{ $register_url }}</a> or clicking the button below.

@component('mail::button', [
    'url' => $register_url,
])
Rejoin Firm Exchange
@endcomponent

We wish you all the best and hope to see you again in the future.
