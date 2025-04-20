<p>Use the <a href="https://ifttt.com/maker" target="_blank">IFTT Maker channel</a> to setup web request.</p>
<ul>
    <li>
        <strong>URL:</strong> {{ action('NewTaskController@index') }}
        <button
        class="js-copy copy-btn btn btn-primary fa fa-copy ifttt-example-url-copy"
        data-clipboard-text="{{ action('NewTaskController@index') }}"
        type="button">
            <span class="sr-only">Copy URL</span>
        </button>
    </li>

    <li>
        <strong>Method:</strong> <code>POST</code>
    </li>

    <li>
        <strong>Content type:</strong> <code>application/json</code>
    </li>

    <li>
        <strong>Body:</strong>
        @include('home.ifttt.request-body', compact('user'))
        <ul>
            <li>
                The example above is for the Alexa IFTTT channel. You may have to alter <code><example-placeholder example="AddedItem"></example-placeholder></code> and <code><example-placeholder example="CreateTime"></example-placeholder></code> to match other channels. If you need help <a href="{{ action('SupportContactController@create') }}">let us know</a>.
            </li>
            @if(is_null($user->selected_list))
            <li>
                Select a list above to replace <code>{list}</code> with the Google Tasks list you want to sync.
            </li>
            @endif

            @if(is_null($user->api_token))
            <li>
                Generate an API Token above to replace <code>{api_token}</code>.
            </li>
            @endif
        </ul>
    </li>
</ul>
