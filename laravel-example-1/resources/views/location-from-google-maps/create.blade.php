@extends('layouts.app', ['pageTitle' => 'Quick Add Location'])

@section('content')
<div class="flex items-center justify-center h-full">
    <form action="{{ route('location-from-google-map-link.store') }}" class="w-full" method="POST">
        @csrf
        <div class="flex items-center justify-center w-full h-full">
            <div class="w-3/4">
                <div class="flex items-center w-full mb-4">
                    <label for="link" class="mr-4">Link</label>
                    <div class="relative flex flex-col flex-1 mr-4">
                        <input type="text" name="link" id="link" class="w-full"
                            placeholder="https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z..."
                            value="{{ old('link') }}">
                        @error('link')
                        <div class="absolute bottom-0 -mb-8 text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="button">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
