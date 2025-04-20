<div class="profile-form-header">
    @if ($type !== 'notifications')
    @include('app/sections/shared/form-action-bar')
    @endif

    @isset($title)
    <h1 class="app-form-title">
        <span id="overlay_tour_form_title_step">{{ $title }}</span>
    </h1>
    @endisset
</div>
