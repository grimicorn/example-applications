@extends('layouts.auth')

@section('auth-title')
    {{ __('Reset Password') }}
@endsection

@section('auth-content')
    <form class="w-full p-6" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="flex flex-wrap mb-6">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                {{ __('E-Mail Address') }}:
            </label>

            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                {{ __('Password') }}:
            </label>

            <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required>

            @if ($errors->has('password'))
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $errors->first('password') }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap mb-6">
            <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">
                {{ __('Confirm Password') }}:
            </label>

            <input id="password-confirm" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required>
        </div>

        <div class="flex flex-wrap justify-center items-center flex-col">
            <button type="submit" class="button">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
@endsection
