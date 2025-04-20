@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
		'pageTitle' => 'Styleguide',
		'pageSubtitle' => 'Parts',
])
@endsection

@section('styleguide-content')

<app-listing-exit-survey-modal
:data-exit-survey="{{ json_encode($space->listing->exitSurvey) }}"
:data-business-sold="false"
:data-auto-open="true"
data-submit-route="{{ route('listing.destroy', ['id' => 116]) }}"></app-listing-exit-survey-modal>

@endsection
