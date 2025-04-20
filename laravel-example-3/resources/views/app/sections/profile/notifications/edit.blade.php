@extends('layouts.application')


@section('content')

@include('app.sections.profile.partials.form-actions')

<div class="profile-notifications-wrap">
    @component('app.sections.profile.partials.form', [
        'type' => 'notifications',
        'formTitle' => 'My Notifications'
    ])

    <app-profile-due-dilligence-notifications
    :data-enable="{{ ((bool) old(
        'emailNotificationSettings.enable_due_diligence', $currentUser->emailNotificationSettings->enable_due_diligence
    )) ? 'true' : 'false' }}"
    :data-digest-enable="{{ ((bool) old(
        'emailNotificationSettings.due_diligence_digest', $currentUser->emailNotificationSettings->due_diligence_digest
    )) ? 'true' : 'false' }}"></app-profile-due-dilligence-notifications>

    <app-profile-blog-post-notifications
    :data-enable="{{ ((bool) old('emailNotificationSettings.blog_posts', $currentUser->emailNotificationSettings->blog_posts
    ))  ? 'true ' : 'false' }}"
    ></app-profile-blog-post-notifications>

    <app-profile-all-notifications
    :data-enable="{{ ((bool) old('emailNotificationSettings.enable_all', $currentUser->emailNotificationSettings->enable_all
    ))  ? 'true ' : 'false' }}"
    ></app-profile-all-notifications>

    <div class="text-right">
        <button type="submit">Save</button>
    </div>
    @endcomponent
</div>
@endsection

