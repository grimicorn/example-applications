@extends('layouts.auth')

@section('auth-title')
    {{ __('Welcome!') }}
@endsection

@section('auth-content')
    <form class="flex justify-center p-6" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="w-3/4">
            <input-text
                data-name="email"
                :data-required="true"
                data-label="{{ __('Username') }}"
                data-error="{{ $errors->first('email') }}"
                data-value="{{ old('email') }}"
            ></input-text>

            <input-text
                data-name="password"
                data-type="password"
                :data-required="true"
                data-label="{{ __('Password') }}"
                data-error="{{ $errors->first('password') }}"
            ></input-text>

            <input-checkbox
                data-name="remember" :value="{{ old('remember') ? 'true' : 'false' }}"
                data-label="{{ __('Remember Me') }}"
            ></input-checkbox>

            <div class="flex flex-wrap justify-center items-center flex-col">
                <button type="submit" class="button">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="text-sm whitespace-no-wrap mt-6" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif

                @if (Route::has('register'))
                    <p class="w-full text-xs text-center text-gray-700 mt-8 -mb-4">
                        Don't have an account?
                        <a href="{{ route('register') }}">
                            Register
                        </a>
                    </p>
                @endif
            </div>
        </div>

    </form>
@endsection
