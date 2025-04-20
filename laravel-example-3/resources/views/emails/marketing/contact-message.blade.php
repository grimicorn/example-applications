@component('mail::message')
# New Contact Submission

- Name: **{{ $fields['name'] }}**
- Phone: **{{ $fields['phone'] }}**
- Email: **{{ $fields['email'] }}**
- Preferred Method of Contact: **{{ $fields['preferred_contact_method'] }}**
- Message: **{{ $fields['message'] }}**

Thanks,<br>
{{ config('app.name') }}
@endcomponent
