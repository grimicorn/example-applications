<p>
Regrettably, your inquiry into "{{ $listing_title }}" has been rejected. The seller indicated that the cause for this rejection was:
</p>

@if($use_listing_closed)
    <p>
        <i>Business Removed</i>
    </p>
@else
    <p>
        <i>{{ $rejection->reason }}</i>
    </p>
@endif

@if($use_listing_closed)
    @if(!$is_default_message and $participant_message)
        <p>
            In addition to the above rationale, the seller asked us to share with you the following:<br>
            <i>{{ $participant_message }}</i><br>
            <a href="{{ $report_abuse_link }}" class="fz-12">Report Abuse</a>
        </p>
    @endif
@else
    @if($rejection->explanation)
        <p>
            In addition to the above rationale, the seller asked us to share with you the following:<br>
            <i>{{ $rejection->explanation }}</i><br>
            <a href="{{ $report_abuse_link }}" class="fz-12">Report Abuse</a>
        </p>
    @endif
@endif

<p>
    If you believe this was an error, we encourage you to reach out to the seller.
</p>
