<div class="page-titles mb3">
    @isset($pageTitle)
        <h1 class="page-title">
            <span id="overlay_tour_page_title_step">{{ $pageTitle }}</span>
        </h1>
    @endisset

    @isset($pageSubtitle)
        <div class="page-subtitle-wrap">
            @isset($pageSubtitleEditRoute)
                    @isset($pageSubtitleEditLabel)
                        <span
                        class="page-subtitle inline-block">{{ $pageSubtitleEditLabel }}&nbsp;</span>
                    @endisset

            <app-edit-subtitle
            route="{{ $pageSubtitleEditRoute }}"
            subtitle="{{ $pageSubtitle }}">
                <template scope="props">
                    <h2
                    class="page-subtitle inline-block"
                    v-text="props.editTitle"></h2>
                </template>
            </app-edit-subtitle>
            @else
                @isset($pageSubtitleLink)
                    <h2 class="page-subtitle">
                        <a href="{{ $pageSubtitleLink }}">{{ $pageSubtitle }}</a>
                    </h2>
                @else
                    <h2 class="page-subtitle">{{ $pageSubtitle }}</h2>
                @endif


            @endisset
        </div>
    @endisset

    @isset($pageSubSubtitle)
        <h3 class="page-sub-subtitle">
            @isset($pageSubSubtitleLink)
                <a href="{{ $pageSubSubtitleLink }}">{{ $pageSubSubtitle }}</a>
            @else
                {{ $pageSubSubtitle }}
            @endif
        </h3>
    @endisset
</div>
