@if(session('alert_success'))
<div
class="alert alert-success alert-dismissible"
role="alert">
    {{ session('alert_success') }}
    <button
    type="button"
    class="close"
    data-dismiss="alert"
    aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
