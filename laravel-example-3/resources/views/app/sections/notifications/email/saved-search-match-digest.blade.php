There are new listing(s) that match the selected criteria for one of your saved searches.  The details are as follows:

<ul style="list-style:none;padding:0;clear:both;">
    @foreach ($searches as $search)
        @if($search->hasNewListings())
            <li>
                <strong>Search name:</strong> {{ $search->name }}<br>

                <strong>Matches:</strong>
                <ul style="list-style:none;padding:0;margin-bottom:15px">
                    @foreach ($search->newListings as $listing)
                    <li>
                        <a href="{{ $listing->show_url }}">{{ $listing->title }}</a>
                    </li>
                    @endforeach
                </ul>

                <p style="margin-bottom:-15px">
                    Click the button below to visit the saved search.
                </p>

                <div style="float:left">
                    @component('mail::button', [
                        'url' => $search->show_url,
                    ])
                        View Saved Search
                    @endcomponent
                </div>
                <div style="clear:both;"></div>
            </li>
        @endif
    @endforeach
</ul>
