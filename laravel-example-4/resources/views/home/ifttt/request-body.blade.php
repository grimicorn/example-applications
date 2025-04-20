<div class="ifttt-request-body">
    <button
    class="js-copy copy-btn btn btn-primary fa fa-copy"
    type="button"
    data-clipboard-target="#ifttt_request_body">
        <span class="sr-only">Copy Request</span>
    </button>

<pre id="ifttt_request_body">
{
    "item": "<example-placeholder example="AddedItem"></example-placeholder>",
    "created_at": "<example-placeholder example="CreateTime"></example-placeholder>",
    "source": "ifttt",
    "list": "{{ is_null($user->selected_list) ? '{list}' : $user->selected_list }}",
    "token": "{{ is_null($user->api_token) ? '{api_token}' : $user->api_token }}"
}
</pre>
</div>
