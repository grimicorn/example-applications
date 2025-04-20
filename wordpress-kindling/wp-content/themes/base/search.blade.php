@extends('layout.base')
@section('content')
    <div class="container mb-10">
        <h1 class="mb-4 md:mb-8">Search Results {{ get_search_query() ? 'for &ldquo;' : '' }}{!! '<span class="text-theme-2">' . get_search_query() . '</span>' !!}{{ get_search_query() ? '&rdquo;' : '' }}</h1>
        <div class="search-roll">
            @while(have_posts())
                {{ the_post() }}
                <div class="search-roll-item">
                    <h2 class="mb-4 leading-none">
                        <a href="{{ get_permalink() }}">{{ get_field('title_override') ?: get_the_title() }}</a>
                    </h2>

                    @if ($subtitle = get_field('subtitle') )
                        <h3 class="leading-none mb-6 text-xl">
                            <a href="{{ get_permalink() }}">{{ $subtitle }}</a>
                        </h3>
                    @endif

                    <div>
                        {{ get_the_excerpt() }}
                        <a href="{{ get_permalink() }}" class="font-bold underline">Read More</a>
                    </div>
                </div>
            @endwhile
            @if(!have_posts())
                <div class="mb-16">
                    <h2 class="mb-8">Sorry, we could not find any results for "<span class="text-theme-1">{{get_search_query()}}</span>".</h2>
                    <p class=" text-black mb-8">You can go <a href="{{home_url()}}">home</a>, or you can try another search below.</p>
                    <?php get_search_form() ?>
                </div>
            @endif
        </div>

        @include('partials.pagination')
    </div>
@endsection
