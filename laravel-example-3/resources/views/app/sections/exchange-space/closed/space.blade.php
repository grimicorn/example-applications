@if($buyer_has_left)
    <p>
        This Exchange Space was closed by the buyer.  The buyer was warned that leaving the Exchange Space would close it for all parties and it could not be undone.
    </p>
@elseif($use_listing_closed)
    <p>
        The business "{{ $listing_title }}" was deleted by the seller, which in turned closed all outstanding Exchange Spaces and Business Inquiries.  The seller was warned that this action was permanent and should not be taken lightly.
    </p>
@else
    <p>
        This Exchange Space was deleted by the seller.  The seller was warned that the action was permanent and should not be taken lightly.
    </p>
@endif

@if(!$buyer_has_left)
    <p>
        @if($is_default_message)
            {{ $participant_message }}
        @else
            Additionally, the seller asked us to share with you the following:<br>
            <i>{{ $participant_message }}</i><br>
            <a href="{{ $report_abuse_link }}" class="fz-12">Report Abuse</a>
        @endif
    </p>
@endif
