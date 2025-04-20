@component('mail::message')
Dear {{ $professional->name }},

{{ $fields['name'] }} has sent you a message through the Firm Exchange platform.  If you wish to respond to the message, please use the contact information provided below.  Please note that we do not monitor user’s correspondence.  To report abuse, please forward this e-mail to <a href="mailto:support@firmexchange.com">support@firmexchange.com</a> with an indication of the abusive behavior.

The Firm Exchange Team

- Name: {{ $fields['name'] }}
@if($fields['phone'])
- Phone: {{ $fields['phone'] }}
@endif
@if($fields['email'])
- Email: <a href="mailto:{{ $fields['email'] }}">{{ $fields['email'] }}</a>
@endif
- Message: {{ $fields['message'] }}

@slot('subcopy')
You are receiving this notification email as a registered user of firmexchange.com. If you would like to modify your notification settings, you can do so via the notifications tab in your user profile.
<br><br>
This is an automatically generated e-mail sent from a mailbox that IS NOT monitored.  If you have any questions or comments regarding this email, please contact us at <a href="mailto:support@firmexchange.com">support@firmexchange.com</a>
@endslot
@endcomponent
