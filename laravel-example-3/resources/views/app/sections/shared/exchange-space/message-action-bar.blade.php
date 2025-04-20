@section('before-content')
@component('app.sections.shared.action-bar')
@slot('left')
    <a
    href="{{ $backLink }}"
    class="a-color7 fw-semibold">
        <i class="fa fa-chevron-left mr0" aria-hidden="true"></i>
        <span class="a-ul">{{ $backLabel }}</span>
    </a>

@endslot
@slot('right')
    @if(!$space->accepted() and optional($space->currentMember)->is_seller)
    <app-exchange-space-accept-inquiry
    :inquiry="{{ $space }}"></app-exchange-space-accept-inquiry>

    <app-exchange-space-reject-inquiry
    :inquiry="{{ $space }}"></app-exchange-space-reject-inquiry>
    @endif
@endslot
@endcomponent
@endsection
