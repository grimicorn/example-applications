@php
    global $wp_query;
    $pageCount = $wp_query->max_num_pages;
    $currentPage = max(1, get_query_var('paged'));
@endphp
@if($pageCount > 1)
    <div class="flex justify-end items-center mb-16">
        {!!
            paginate_links([
                'base' => str_replace($big = 999999999, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '&page=%#%',
                'current' => $currentPage,
                'total' => $pageCount,
                'prev_text' => __('<i class="fas fa-chevron-left"></i>'),
                'next_text' => __('<i class="fas fa-chevron-right"></i>'),
            ]);
        !!}
    </div>
@endif
