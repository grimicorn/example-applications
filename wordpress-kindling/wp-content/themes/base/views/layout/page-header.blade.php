@php
$page_id = get_queried_object_id();
$title = get_field('title_override',$page_id);
$title = $title ? $title : kindling_page_title();
@endphp
<div class="page-header mb-8">
    <div class="container">
        <h1>{{$title}}</h1>
        {!! the_post_thumbnail('featured-image') !!}
    </div>
</div>
