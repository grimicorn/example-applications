<h1
    dusk="app_title"
    class="text-brand-darker {{ isset($subtitle) ? '' : 'mb-4' }}"
>
    @isset($title_link)
        <a
            href="{{ $title_link }}"
            target="{{ $title_link_target ?? '_self'}}"
        >{{ $title }}</a>
    @else
        {{ $title }}
    @endisset
</h1>

@isset($subtitle)
    <h2
        dusk="app_subtitle"
        class="text-brand mb-4 text-base"
    >
        @isset($subtitle_link)
            <a
                href="{{ $subtitle_link }}"
                target="{{ $subtitle_link_target ?? '_self'}}"
            >{{ $subtitle }}</a>
        @else
            {{ $subtitle }}
        @endisset
    </h2>
@endisset
