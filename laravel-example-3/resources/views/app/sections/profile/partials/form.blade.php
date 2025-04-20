@include('app.sections.shared.form', [
    'section' => 'profile',
    'type' => $type,
    'action' => isset($route) ? route($route) : route("profile.{$type}.update"),
    'hasFormHeader' => isset($hasFormHeader) ? $hasFormHeader : true,
    'shouldAjax' => isset($shouldAjax) ? $shouldAjax : false,
])
