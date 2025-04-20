<h3>1. Generate an API Token</h3>

<form method="POST" class="api-token-form" action="/token/generate">
    {{ csrf_field() }}

    {{ method_field('PUT') }}

    <input
    type="text"
    id="api_token"
    name="api_token"
    readonly="readonly"
    placeholder="API Token"
    class="api-token-input"
    size="44"
    value="{{ $user->api_token }}">

    @if($user->api_token)
    <button
    class="js-copy copy-btn btn btn-primary fa fa-copy api-token-input-copy"
    data-clipboard-text="{{ $user->api_token }}"
    type="button">
        <span class="sr-only">Copy API Token</span>
    </button>
    @endif

    <button
    type="submit"
    class="btn btn-primary">
        {{ is_null($user->api_token) ? 'Generate' : 'Regenerate' }}
    </button>
</form>
