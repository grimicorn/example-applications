@if(!isset($isCreate) or !$isCreate)
<div class="flex items-center mb3">
    <div class="flex-auto">
        @isset($subtitle)
        <span class="fz-24 mb1 fc-color6">{{ $subtitle }}</span>
        @endisset

        @isset($title)
        <h3 class="fc-color4 fz-30 text-def-style mb0">{{ $title }}</h3>
        @endisset
    </div>
    <print-page></print-page>
</div>
@endif

@isset($messages)
@foreach($messages as $message)
@include('app/sections/shared/exchange-space/message', [
    'message' => $message,
])
@endforeach
@endisset
