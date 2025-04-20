@extends('layouts.app')

@section('content')
    @include('partials.app-title', [
        'title' => $site->name,
    ])

    <site-page-data-table :data-site="{{ $site->toJson() }}"></site-page-data-table>
@endsection
