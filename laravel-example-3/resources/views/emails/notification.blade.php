@component('mail::message')

{{ $notification->emailHeader() }}

{!! $notification->emailBody() !!}
<br><br>
{!! $notification->emailFooter() !!}

@slot('subcopy')
{!! $notification->subFooter() !!}
@endslot
@endcomponent
