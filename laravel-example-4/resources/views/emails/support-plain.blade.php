Hello,

@foreach ($data as $key => $value)
- {{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}
@endforeach

Regards,
{{ config('app.name') }}
