@extends('layouts.base')

@section('head')
<script>
    window.srcWatch = {!! javascriptVars()->toJson() !!}
</script>
@endsection

@section('body')
    <app-page class="flex h-full">
        <div class="w-48">
            <div class="fixed px-4 w-48 bg-brand-darker pin overflow-scroll">
                <logo
                    :data-link="route('dashboard')"
                    class="h-12"
                ></logo>

                <app-menu></app-menu>
            </div>
        </div>

        <div class="flex-1 flex-col px-4 h-full">
            <app-header></app-header>

            <div class="flex-1 mb-4 p-4">
                @yield('content')
            </div>

            @include('partials.footer')
        </div>
    </app-page>
@endsection
