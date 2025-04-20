
<app-profile-settings-update-password
    username="{{ $currentUser->email }}"
    :display-fields="{{ old('password_update') ? 'true' : 'false' }}"
    route="{{ route('profile.settings.update') }}"
    :submission-errors="{{ json_encode([
        'current_password' => $errors->first('current_password'),
        'password' => $errors->first('password'),
        'password_confirmation' => $errors->first('password_confirmation'),
    ]) }}"
    :values="{{ json_encode([
        'current_password' => old('current_password'),
        'password' => old('password'),
        'password_confirmation' => old('password_confirmation'),
    ]) }}"></app-profile-settings-update-password>
