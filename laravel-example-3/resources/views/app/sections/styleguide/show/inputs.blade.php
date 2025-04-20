@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
    'pageTitle' => 'Styleguide',
    'pageSubtitle' => 'Inputs',
])
@endsection

@section('styleguide-content')

<p>
    Form components can be found in <code>/resources/assets/js/components/forms</code>. This view can be found at <code>/resources/views/app/styleguide/show/form.blade.php</code> if you would like to see each basic component.
</p>

<p>
    <code>fe-form.vue</code> form wrapper element.
</p>

<styleguide-form-example
:data-force-validation-errors="{{ old('force_validation_errors') ? 'true' : 'false' }}"
:data-submit-via-ajax="{{ old('submit_via_ajax') ? 'true' : 'false' }}"
:data-disabled-unload="{{ old('disabled_unload') ? 'true' : 'false' }}"></styleguide-form-example>

<h2 class="mt1">Example of confirmation submit</h2>
{{ request()->get('example_of_confirmation_submit_text') }}
<fe-form
    form-id="example_of_confirmation_submit"
    action=""
    method="POST"
    :disabled-unload="true"
    data-challenge-label="Type CHECK to enable submission. Type CHECK to enable submission. Type CHECK to enable submission. Type CHECK to enable submission."
    submit-confirm-challenge="CHECK">

    <input
    type="hidden"
    name="example_of_confirmation_submit_text"
    value="The Example of confirmation submit has been successfully submitted">
</fe-form>

<styleguide-search-example></styleguide-search-example>

@endsection
