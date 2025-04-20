@extends('layouts.app')

@section('content')
<div class="bg-white">
    @include('partials.app-title', [
        'title' => 'My Dashboard',
    ])

    <div class="flex -mx-4">
        <div class="w-1/2 px-4">
        </div>

        <div class="w-1/2 px-4">
            <broadcast-listener></broadcast-listener>
        </div>
    </div>
</div>
@endsection
