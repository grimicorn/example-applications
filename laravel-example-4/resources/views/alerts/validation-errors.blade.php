@unless(empty($errors->all()))
@foreach ($errors->all() as $message)
<div
class="alert alert-danger alert-dismissible validation-error-alert"
role="alert">
    {{ $message }}
    <button
    type="button"
    class="close"
    data-dismiss="alert"
    aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@endforeach
@endunless
