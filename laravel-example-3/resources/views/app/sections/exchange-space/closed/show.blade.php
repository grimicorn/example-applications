@extends('layouts.application')

@section('content')
    @if($rejected)
        @include('app.sections.exchange-space.closed.inquiry')
    @else
        @include('app.sections.exchange-space.closed.space')
    @endif
@endsection
