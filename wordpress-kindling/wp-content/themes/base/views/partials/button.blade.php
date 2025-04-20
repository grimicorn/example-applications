@if( ($button_link and $button_text) ?? '' )
    <a
        class="btn {{$class}}"
        href="{{$button_link}}"
        target="{{$button_new_window === true ? '_blank' : '_self'}}"
    >
        {!! $button_text !!}
    </a>
@endif
