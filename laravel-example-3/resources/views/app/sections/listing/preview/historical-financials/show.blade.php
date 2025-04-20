@extends('layouts.application')


@section('before-content')
    @component('app.sections.shared.action-bar')
        @slot('left')
            <app-model-preview-close></app-model-preview-close>
        @endslot
        @slot('right')
        @endslot
    @endcomponent
@endsection


@section('content')
    @include('app.sections.exchange-space.historical-financials.table')
@endsection
