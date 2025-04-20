@extends('layouts.auth')

@section('auth-title')
    {{ __('Reset Password') }}
@endsection

@section('auth-content')
    <form class="w-full p-6" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="flex flex-wrap mb-6">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                {{ __('E-Mail Address') }}:
            </label>

            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <p class="text-red-100 text-xs italic mt-4">
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap justify-center items-center flex-col">
            <button type="submit" class="button">
                {{ __('Send Password Reset Link') }}
            </button>

            <p class="w-full text-xs text-center text-grey-dark mt-8 -mb-4">
                <a class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('login') }}">
                    Back to login
                </a>
            </p>
        </div>
    </form>
@endsection
