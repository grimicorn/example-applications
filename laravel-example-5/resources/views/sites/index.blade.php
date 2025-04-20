@extends('layouts.app')

@section('content')
    @include('partials.app-title', [
        'title' => 'Sites',
    ])

    <site-data-table></site-data-table>
@endsection
