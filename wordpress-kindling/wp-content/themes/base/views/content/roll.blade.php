<div class="container">
    <div class="content-roll">
        @while(have_posts())
            {{ the_post() }}
            @include('content.roll-item')
        @endwhile
    </div>
</div>
