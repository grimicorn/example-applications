@component('mail::message')
# Business Inquiry Rejected

**Rejected because:** {{ $reason }}

{{ $explanation }}

@component('mail::button', ['url' => $space->show_url])
View Business Inquiry
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
