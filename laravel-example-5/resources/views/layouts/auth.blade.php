@extends('layouts.base')

@section('body')
    <div class="circuit-border-bg">
        @include('partials.auth-header')
        <div class="min-h-screen flex items-center justify-center pt-12 px-4">
            <div class="w-full">
                @isset($title)
                    <h1 dusk="auth_title" class="text-brand-darker mb-4 text-center text-xl">
                        {{ $title }}
                    </h1>
                @endisset

                <div class="px-4 py-6 bg-white rounded-lg shadow mx-auto xl:w-1/3 md:w-1/2">
                    @yield('content')
                </div>

                @include('partials.footer')
            </div>

        </div>
    </div>
@endsection
