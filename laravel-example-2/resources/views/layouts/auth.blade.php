@extends('layouts.app', [
    'display_header' => false,
    'body_class' => 'bg-auth',
])

@section('content')
    <div class="flex flex-wrap h-full items-center justify-center">
        <div class="w-full max-w-2xl">
            <div class="flex justify-center items-center mb-8 text-white uppercase">
                <img class="login-logo" src="{{ url('/images/fnc-logo.png') }}">
                <span class="text-2xl px-4">|</span>
                <span class="text-xl font-bold leading-none">VMS</span>
            </div>

            <alert data-type="info" :data-dismissable="true">
                {{ session('status') }}
            </alert>

            <div class="flex justify-center items-center break-words bg-white border border rounded-sm border-black px-8 py-16 w-full">


                <div class="max-w-md flex flex-col w-full">
                    <div class="text-center text-4xl mb-6">
                        @yield('auth-title')
                    </div>
                    <div class="auth-content">
                        @yield('auth-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

