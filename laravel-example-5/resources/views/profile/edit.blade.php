@extends('layouts.app')

@section('content')
@include('partials.app-title', [
    'title' => 'My Profile',
])

<ajax-form
    class="clearfix"
    data-id="profile_edit"
    data-method="PATCH"
    data-action="{{ route('profile.update', ['user' => $user]) }}"
    data-submit-label="Update"
>
    <template slot="inputs">
        <input-text
            data-label="First Name"
            data-name="first_name"
            data-placeholder="First"
            data-value="{{ $user->first_name }}"
            :data-required="true"
            class="md:w-1/2 md:float-left md:pr-2"
        ></input-text>

        <input-text
            data-label="Last Name"
            data-name="last_name"
            data-placeholder="Last"
            data-value="{{ $user->last_name }}"
            :data-required="true"
            class="md:w-1/2 md:float-left md:pl-2"
        ></input-text>

        <input-text
            data-type="email"
            data-label="Email"
            data-name="email"
            data-placeholder="you@domain.com"
            data-value="{{ $user->email }}"
            :data-required="true"
            class="md:w-1/2 md:float-left clear md:pr-2"
        ></input-text>

        <input-image
            class="md:w-1/2 md:float-left md:pl-2"
            data-label="Avatar"
            data-name="avatar"
            data-id="avatar"
            :data-value="{
                alt: 'Avatar',
                src: '{{ auth()->user()->avatar }}',
            }"
        ></input-image>

        <input-text
            data-type="password"
            data-label="Password"
            data-name="password"
            data-placeholder="my$uperC0mplexPa22word"
            data-instructions="Leave blank to keep your password the same."
            class="clear md:w-1/2 md:float-left md:pr-2"
        ></input-text>

        <input-text
            data-type="password"
            data-label="Confirm Password"
            data-name="password_confirmation"
            data-placeholder="my$uperC0mplexPa22word"
            class="md:w-1/2 md:float-left md:pl-2"
        ></input-text>
    </template>
</ajax-form>
@endsection
