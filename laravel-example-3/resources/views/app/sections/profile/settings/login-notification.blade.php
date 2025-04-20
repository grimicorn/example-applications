<div class="profile-settings-login-notification clearfix">
   <fe-form
    form-id="profile-settings-login-notification-form"
    class="profile-settings-login-notification-form"
    method="PATCH"
    submit-label="Update"
    action="{{ route('profile.notifications.update') }}">

        <div class="profile-settings-login-notification-info">
            <h3>Login Notification:</h3>
            <p>
                With this setting enabled, you will receive a notification if our system detects a sign-in from a new device. This notification can also be triggered if you clear your cookies, use a different internet browser, or use your browser's privacy mode (e.g. Incognito Mode).
            </p>
        </div>

        <input-toggle
        name="emailNotificationSettings[enable_login]"
        value="{{  old('emailNotificationSettings.enable_login', $currentUser->emailNotificationSettings->enable_login) }}"></input-toggle>
    </fe-form>
</div>
