@component('mail::message')
# New abuse report

- **Reporter Name:** {{ $link->reporter->name }}
- **Reporter Email:** {{ $link->reporter->email }}
@if($link->reason)
- **Reason:** {{ $link->reason }}
@endif
@if($link->reason_details)
- **Reason Details:** {{ $link->reason_details }}
@endif

### Message Details
- **Creator Name:** {{ $link->creator->name }}
- **Creator Email:** {{ $link->creator->email }}
- **Message:** {{ $link->message }}
- **Notification Type:** {{ $link->notification_type_label }}

@if($link->action_url and $link->action_label)
    @component('mail::button', ['url' => $link->action_url])
        {{ $link->action_label }}
    @endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent

