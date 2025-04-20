@extends('layout.base')

@section('content')
    @include('layout.page-header')
    <div class="container">
        @include('content.loop')
    </div>
@endsection
