@extends('layouts.application')

@section('content')

@include('app.sections.profile.partials.form-actions')

<div class="profile-edit-wrap">
    @component('app.sections.profile.partials.form', [
        'type' => 'edit',
        'route' => 'profile.update',
        'formTitle' => 'My Profile',
        'shouldAjax' => true,
    ])
        @include('app.sections.profile.edit.partials.personal-information')
        @include('app.sections.profile.edit.partials.desired-purchase-criteria')
        @include('app.sections.profile.edit.partials.professional-information')
    @endcomponent
</div>
@endsection

@section('sidebar')
    <app-sticky-form-buttons>
        <app-teleport-click-button
        class="width-100 mb2"
        data-label="Save"
        data-to-selector=".btn-model-submit"></app-teleport-click-button>

        <app-teleport-click-button
        class="width-100 btn-color5"
        data-label="Preview"
        data-to-selector=".model-preview-button"></app-teleport-click-button>
    </app-sticky-form-buttons>
@endsection
