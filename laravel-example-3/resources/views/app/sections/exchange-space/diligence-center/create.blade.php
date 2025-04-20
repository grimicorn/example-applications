@extends('layouts.application')

@section('before-content')
@component('app.sections.shared.action-bar')
@slot('left')
Left
@endslot
@slot('right')
Right
@endslot
@endcomponent
@endsection

@section('content')
@endsection
