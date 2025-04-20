@php
global $post;

$current = $post->ID;
$parent = $post->post_parent;
@endphp
<div class="content-loop js-fitvid">
    @while(have_posts())

        {{ the_post() }}

        {{ the_content() }}

    @endwhile
</div>
