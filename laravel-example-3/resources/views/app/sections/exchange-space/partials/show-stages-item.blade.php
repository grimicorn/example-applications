
{{-- Bar/circle node before a circled number/label --}}
@if(isset($first) and !$first)
    <div class="pb--bar inline-block {{ $first_fill_class }}"></div>
@endif

<div class="text-center inline-block relative">
    {{-- Circled number --}}
    <i
    class="pb--common fa fa-check {{ $middle_fill_class }}"
    aria-hidden="true"></i>

    {{-- Label --}}
    @isset($label)
    <div class="pb--label {{ $color_class }} fz-16 lh-solid nowrap">
        <span class="text-uppercase">{{ $label }}</span>
    </div>
    @endisset
</div>

{{-- Bar/circle node after a circled number/label --}}
@if(isset($last) and !$last)
{{-- pb--node--filled pb--bar--filled --}}
<div class="pb--bar pb--bar--node inline-block {{ $last_fill_class }}">
<div
class="pb--bar--node--tooltip"
direction="bottom"
:auto-open="{{
    session('stage_advanced_to') === $stage ? 'true' : 'false'
}}">
    <div class="pb--bar--node--circle" slot="icon">
        @if($intermediate_label)
            <span class="pb--bar--node--circle--label">{{ $intermediate_label }}</span>
        @endif
        <span class="fa fa-circle pb--bar--node--circle--icon"></span>
    </div>

</div>

</div>
@endif
