@php
// Template Name: Page Builder
@endphp

@extends('layout.base')

@section('content')
    @include('layout.page-header')
    {!! kindling_page_builder_loader() !!}
@endsection
