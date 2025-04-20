@php
$shouldAjax = isset($shouldAjax) ? $shouldAjax : false;
@endphp
<fe-form
form-id="application-{{ $section }}-{{ $type }}"
:remove-submit="true"
:should-ajax="{{ $shouldAjax ? 'true' : 'false' }}"
action="{{ isset($action) ? $action : '' }}"
method="{{ isset($method) ? $method : 'PATCH' }}"
class="application-{{ $section }}-{{ $type }} app-fe-form"
:enable-redirect="{{ (isset($enableRedirect) and $enableRedirect) ? 'true' : 'false' }}">
    @if (isset($hasFormHeader) and $hasFormHeader)
    @include('app.sections.profile.partials.form-header', [
             'title' => isset($formTitle) ? $formTitle : null,
    ])
    @endif

    {{ $slot }}
</fe-form>
