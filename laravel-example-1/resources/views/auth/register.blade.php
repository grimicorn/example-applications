@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                    <div class="px-6 py-3 mb-0 font-semibold text-gray-700 bg-gray-200">
                        {{ __('Register') }}
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="flex flex-wrap mb-6">
                            <label for="name" class="block mb-2 text-sm font-bold text-gray-700">
                                {{ __('Name') }}:
                            </label>

                            <input id="name" type="text" class="w-full @error('name')  border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <p class="mt-4 text-xs italic text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="email" class="block mb-2 text-sm font-bold text-gray-700">
                                {{ __('E-Mail Address') }}:
                            </label>

                            <input id="email" type="email" class="w-full @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <p class="mt-4 text-xs italic text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="password" class="block mb-2 text-sm font-bold text-gray-700">
                                {{ __('Password') }}:
                            </label>

                            <input id="password" type="password" class="w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <p class="mt-4 text-xs italic text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="password-confirm" class="block mb-2 text-sm font-bold text-gray-700">
                                {{ __('Confirm Password') }}:
                            </label>

                            <input id="password-confirm" type="password" class="w-full" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="flex flex-wrap">
                            <button type="submit" class="inline-block px-4 py-2 text-base font-bold leading-normal text-center text-gray-100 no-underline whitespace-no-wrap align-middle border rounded select-none bg-primary-500 hover:bg-primary-700">
                                {{ __('Register') }}
                            </button>

                            <p class="w-full mt-8 -mb-4 text-xs text-center text-gray-700">
                                {{ __('Already have an account?') }}
                                <a class="no-underline text-primary-500 hover:text-primary-700" href="{{ route('login') }}">
                                    {{ __('Login') }}
                                </a>
                            </p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
