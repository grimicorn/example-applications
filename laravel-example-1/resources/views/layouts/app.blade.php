<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($pageTitle))
    <title>{{ $pageTitle }} | {{ config('app.name', 'Laravel') }}</title>
    @else
    <title>{{ config('app.name', 'Laravel') }}</title>
    @endif

    <!-- Scripts -->
    <script>
        window.Domain = {
            user: {
                currentLocation: {!! json_encode(optional(Auth::user())->currentLocation ?? '{}') !!},
                searchAddressDistance: {{ optional(Auth::user())->search_address_distance ?? 'undefined' }}
            },
            locationTags: {!! App\Models\Tag::all(['name', 'id'])->toJson() !!},
            locationIcons: {!! \App\Models\LocationIcon::all()->toJson() !!},
            forms: {
                old: {!! json_encode(session()->getOldInput()) !!},
                errors: {!! $errors->toJson() !!},
            },
            timesOfYearToVisit: {!! json_encode(resolve(\App\Domain\Supports\BestVisitTimes::class)->ofYear()) !!},
            timesOfDayToVisit: {!! json_encode(resolve(\App\Domain\Supports\BestVisitTimes::class)->ofDay()) !!},
            globalAlerts: [
                @if (session('success_message'))
                    {
                        timeout: 5000,
                        type: "success",
                        message: "{{ html_entity_decode(session('success_message')) }}",
                    }
                @endif
            ],
            locationTrafficLevels: {!! collect(\App\Enums\LocationTrafficLevelEnum::toArray())->map(function ($value) {
                return collect([
                    'value' => $value,
                    'label' => str_replace('_', ' ', \Illuminate\Support\Str::title($value)),
                ]);
            })->values()->toJson() !!},
            locationAccessDifficulties: {!! collect(\App\Enums\LocationAccessDifficultyEnum::toArray())->map(function ($value) {
                return collect([
                    'value' => $value,
                    'label' => str_replace('_', ' ', \Illuminate\Support\Str::title($value)),
                ]);
            })->values()->toJson() !!},
        }
    </script>
    @yield('scripts')
    @routes
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="h-screen antialiased leading-none bg-gray-100">
    <div id="app" class="flex flex-col h-full">
        <nav class="py-6 mb-8 shadow bg-primary-900">
            <div class="container">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <div class="flex items-center ml-auto text-right">
                        @guest
                        <a class="p-3 text-sm text-gray-300 no-underline hover:underline"
                            href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                        <a class="p-3 text-sm text-gray-300 no-underline hover:underline"
                            href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                        @else
                        <a href="{{ route('location-from-google-map-link.create') }}"
                            class="mr-3 button-sm button-inverse">
                            Quick Add
                        </a>
                        <a href="{{ route('locations.create') }}" class="mr-6 button-sm button-inverse">
                            Add location
                        </a>

                        <span class="flex items-center pr-4 text-sm text-gray-300">
                            <img src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}?s=20"
                                alt="{{ Auth::user()->name }} Image" class="mr-2 rounded-full">
                            {{ Auth::user()->name }}
                        </span>

                        <a href="{{ route('logout') }}" class="p-3 text-sm text-gray-300 no-underline hover:underline"
                            onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <div class="container flex-1">
            @yield('content')
        </div>

        <div class="flex items-center justify-center py-8">
            Made with
            <icon data-name="heart" class="w-3 h-3 mx-1 text-danger" data-animation="pulse"></icon> in
            St.Louis | &copy; <a href="https://danholloran.me" target="_blank" class="mx-1">Dan Holloran</a>
            {{ date('Y') }}
        </div>

        {{-- Global Alerts --}}
        <global-alerts></global-alerts>

        <back-to-top></back-to-top>
    </div>
</body>

</html>
