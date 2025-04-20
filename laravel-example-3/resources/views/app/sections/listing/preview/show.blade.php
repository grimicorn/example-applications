@extends('layouts.application')

@section('scripts')
<link href="{{ mix('css/marketing.css') }}" rel="stylesheet">
@endsection

@section('before-content')
    @component('app.sections.shared.action-bar')
        @slot('left')
            <app-model-preview-close></app-model-preview-close>
        @endslot
    @endcomponent
@endsection


@section('content')
@include('marketing.businesses.single-business', ['isAdmin' => true])
@endsection
