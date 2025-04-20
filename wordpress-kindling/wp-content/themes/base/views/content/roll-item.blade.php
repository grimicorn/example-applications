@php
$post_link = get_post_permalink();
@endphp
<div class="roll-item mb-8">
    <a href="{{ $post_link }}">
        {{ the_post_thumbnail('blog-thumbnail') }}
    </a>

    <a href="{{ $post_link }}">
    <h2 class="roll-item-title">{{ the_title() }}</h2>
    </a>
    <div class="flex mb-4">

        <div class="mr-4">{{get_the_date()}}</div>

        {{the_category(',')}}
    </div>
    <div class="roll-item-content">
        {{ the_excerpt() }}
    </div>



    <a href="{{ $post_link }}">Read more &raquo;</a>
</div>
