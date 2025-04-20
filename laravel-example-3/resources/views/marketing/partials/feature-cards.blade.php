@if(!isset($hasBackground) || $hasBackground)
<div class="featured-cards-bg">
@endif
    <div class="featured-cards container">
        @isset($title)
        <h2 class="featured-cards-title text-center section-title">{{ $title }}</h2>
        @endisset

        @isset($content)
        <p class="text-center featured-cards-content">
            {{ $content }}
        </p>
        @endisset

        <div class="row">
            @foreach($cards as $card)
            <div
            class="featured-card text-center {{ isset($colClass) ? $colClass : 'col-md-4 col-xs-12' }} {{ isset($card['button']) ? 'has-button' : '' }}">
                <div class="inner">
                    @isset($card['iconClass'])
                    <div class="featured-card-icon-wrap">
                        <div class="icon featured-card-icon {{ $card['iconClass'] }}"></div>
                    </div>
                    @endisset

                    @isset($card['title'])
                    <h2 class="featured-card-title">{{ $card['title'] }}</h2>
                    @endisset

                    @isset($card['content'])
                    <div class="featured-card-content">
                        {{ $card['content'] }}
                    </div>
                    @endisset

                    @isset($card['listItems'])
                    <ul class="check-list text-left featured-card-list">
                        @foreach($card['listItems'] as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    @endisset

                    @isset($card['button'])
                    <div class="featured-card-button-wrap">
                        <a
                        href="{{ $card['button']['href'] }}"
                        class="btn btn-color5 featured-card-button">{{ $card['button']['label'] }}</a>
                    </div>
                    @endisset
                </div>
            </div>
            @endforeach
        </div>

        @if(isset($displayDisclaimer) and $displayDisclaimer)
        @include('marketing/partials/disclaimer')
        @endif
    </div>
@if(!isset($hasBackground) || $hasBackground)
</div> {{-- /.featured-cards-bg --}}
@endif
