@if(session('alert_error'))
<div
class="alert alert-danger alert-dismissible"
role="alert">
    {{ session('alert_error') }}
    <button
    type="button"
    class="close"
    data-dismiss="alert"
    aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
