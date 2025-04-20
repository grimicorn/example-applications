@section('form-action-bar-right')
    <app-model-preview-button
    store-route="{{ route('profile.preview') }}"
    :routes="{{ json_encode($previewRoutes ?? []) }}"
    form-id="application-profile-edit"></app-model-preview-button>

    <app-model-save-button
    :is-link="false"></app-model-save-button>
@endsection
