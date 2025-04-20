<?php
/**
 * Theme Filters.
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

/**
 * Clean up the_excerpt()
 */
add_filter('excerpt_more', function () {
    return ' &hellip;';
});

/**
 * Customize the_excerpt() length.
 */
add_filter('excerpt_length', function ($length) {
    return 55;
}, 999);


//this filter adds social media icons to menu items without compromising the title attribute
add_filter('wp_nav_menu_args', function ($args) {
    if ($args['theme_location'] === 'social_media_menu') {
        $args['link_before'] = '{{replace_before}} <span class=sr-only>'; // adds icon, wraps text in span that hides the string
        $args['link_after'] = '</span> {{replace_after}}'; //closes span that wraps text, needs {{repace_after}} to make span unique for removal
    }
    return $args;
});

add_filter('walker_nav_menu_start_el', function ($object_output, $object, $depth, $args) {
    $icon = get_field('social_media_icon', $object) ?? '';
    if (!$icon or $icon === 'none') {
        $object_output = str_replace('{{replace_before}} <span class="sr-only">', '', $object_output); //removes first placehoder and span tag
        $object_output = str_replace('</span> {{replace_after}}', '', $object_output); //removes second placehoder and span tag
        return $object_output;
    }
    $icon =  '<i class="fab ' . $icon . '"></i>' ;
    $object_output = str_replace('{{replace_before}}', $icon, $object_output); //removes first place holder and inserts icon before text string
    $object_output = str_replace('{{replace_after}}', '', $object_output);//removes second placeholder
    return $object_output;
}, 10, 4);

