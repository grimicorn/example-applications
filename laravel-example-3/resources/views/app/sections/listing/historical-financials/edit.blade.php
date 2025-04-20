@php
$most_recent_year = intval(old(
    'hf_most_recent_year',
    optional($listing->hf_most_recent_year)->format('Y')
));
$most_recent_quarter = old('hf_most_recent_quarter', $listing->hf_most_recent_quarter);
$year_established = intval($listing->year_established);
@endphp

@extends('layouts.application')

@include('app.sections.listing.partials.form-actions', [
    'section' => $section,
    'type' => $type,
])

@section('content')

@component('app.sections.shared.form', [
    'section' => $section,
    'type' => $type,
    'action' => route('listing.historical-financials.update', ['id' => $listing->id]),
    'formTitle' => 'Historical Financial Information',
    'hasFormHeader' => true,
    'method' => 'PATCH',
    'shouldAjax' => true,
])

<app-historical-financial-edit
:data-year-options="{{ json_encode($yearRangeforSelect) }}"
:data-most-recent-year="{{ $most_recent_year }}"
{{
    is_null($most_recent_quarter) ? '' : ":data-most-recent-quarter='{$most_recent_quarter}'"
}}
:data-year-established="{{ $year_established }}"
:data-listing="{{ json_encode($listing) }}"
data-form-id="{{ $formId }}"
:data-sections="{{ json_encode($sections) }}"></app-historical-financial-edit>

@endcomponent
@endsection

@section('sidebar')
    <app-sticky-form-buttons>
        <app-teleport-click-button
        class="width-100 mb2"
        data-label="Save"
        data-to-selector=".btn-model-submit"></app-teleport-click-button>

        <app-teleport-click-button
        class="width-100 btn-color5"
        data-label="Save &amp; Preview"
        data-to-selector=".model-preview-button"></app-teleport-click-button>
    </app-sticky-form-buttons>
@endsection
