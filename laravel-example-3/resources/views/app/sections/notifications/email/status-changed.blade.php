The status for Exchange Space {{ $exchange_space_title }} has been updated to "{{ $status_label }}".  To go to the Exchange Space, click the button below.

@component('mail::button', [
    'url' => $exchange_space_url,
])
View Exchange Space
@endcomponent

Members can use the <a href="{{ $diligence_center_url }}" target="_blank">Diligence Center</a> to ask and answer any questions they might have about the business in question or the deal itself.  By using the Diligence Center (as opposed to e-mails or phone calls), all members of an Exchange Space can stay in the loop.  This stage of the deal often includes preliminary negotiations and concludes with a signed Letter of Intent.

For more information, see our <a href="{{ $blog_url }}" target="_blank">blog</a> for articles that can help you navigate the deal process.
