@php
$wrappers = is_array($wrappers ?? []) ? $wrappers : [];
if (!isset($hide)) {
    $hide = r_collect($wrappers)->pluck('hide')->filter(function ($item) {
        return is_null($item) ? true : !$item;
    })->isEmpty();
}
@endphp

@if(!isset($hide) ||  !$hide)
    @if($title)
        <div class="row">
            <h2 class="col-xs-12 section-content-title"> {{$title}}</h2>
        </div>
    @endif

    @foreach($wrappers as $wrapper)
        @if(!isset($wrapper['hide']) || !$wrapper['hide'])
        <div class="row mb2-m">
            <div class="col-sm-3 section-content-label fz-16 lh-title print-25 print-pull-left">{{$wrapper['label']}}:</div>
            <div class="col-sm-9 lh-copy print-75 print-pull-right">
                @if(!isset($wrapper['isLink']) || !$wrapper['isLink'])
                    {!! nl2br(e($wrapper['content']))!!}
                    {{isset($wrapper['content_2']) && $wrapper['content'] === 'Yes' ? "- ".nl2br(e($wrapper['content_2'])) : ''}}
                @elseif(isset($wrapper['content']))
                    @foreach($wrapper['content'] as $item)
                        <a href="{{@$item}}" target="_blank" class="linked">{{ $item }}</a><br>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
    @endforeach
@endif
