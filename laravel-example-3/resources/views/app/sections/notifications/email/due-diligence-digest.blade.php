There was Diligence Center activity in one or more of the Exchange Spaces of which you’re a member.  To review the message(s), visit your dashboard by clicking the button below or by clicking the Diligence Center link(s) below.

@component('mail::button', [
    'url' => $dashboard_url,
])
Dashboard
@endcomponent

@isset($summary_items)
@foreach ($summary_items as $summary)
<ul style="list-style:none">
    <li>
        <strong>Exchange Space:</strong>
        <a href="{{ optional($summary->get('space'))->show_url }}">
            {{ optional($summary->get('member'))->custom_title }}
        </a>
    </li>
    @foreach ($summary->get('conversations') as $conversation)
        <li>
            {{ $conversation->digest_count }} message(s) have been posted in <a href="{{ $conversation->show_url }}">{{ $conversation->title }}</a>
        </li>
    @endforeach
</ul>
@endforeach
@endisset
