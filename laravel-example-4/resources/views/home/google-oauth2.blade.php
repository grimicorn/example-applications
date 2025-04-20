<h3>2. Enable Google Tasks access</h3>
@if (is_null($user->google_access_token)  || $google_access_token_expired)
<a
href="/oauth2/google/request"
class="google-oauth2-btn btn btn-default">
<span class="fa fa-google"></span> Authorize Google Tasks Access</a>
@else
<a
href="/oauth2/google/revoke"
class="google-oauth2-btn btn btn-default">
<span class="fa fa-google"></span> Revoke Google Tasks Access</a>
@endif
