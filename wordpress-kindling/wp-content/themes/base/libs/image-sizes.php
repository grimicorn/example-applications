<?php
/**
 * Theme Image Sizes.
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

add_filter('kindling_image_sizes', function ($sizes) {
    // // Example Image Size
    // $sizes[] = [
    //     // The size name to be used when displaying an image.
    //     // Default is "{$width}x{$height}".
    //     // wp_get_attachment_image($attachment_id, $size = 'example-image-size');
    //     'name' => 'example-image-size',

    //     // Sets the images width use '' to make it auto.
    //     // Default is ''.
    //     'width' => 1200,

    //     // Sets the images height use '' to make it auto.
    //     // Default is ''.
    //     'height' => 500,

    //     // Controls if the image should be cropped.
    //     // Default is true.
    //     'crop' => true,

    //     // Controls the ability to be added to the size selector in the media manager.
    //     // Default is true.
    //     'enable_choose' => true,
    // ];

    // Featured Image Administrator Column Image Size.
    $sizes[] = [
        'name' => 'admin-list-thumb',
        'width' => 100,
        'height' => 100,
        'crop' => true,
        'enable_choose' => false,
    ];

    $sizes[] = [
        'name' => 'featured-image',
        'width' => 1200,
        'height' => 1000,
        'crop' => true,
        'enable_choose' => false,
    ];
    $sizes[] = [
        'name' => 'blog-thumbnail',
        'width' => 300,
        'height' => 300,
        'crop' => true,
        'enable_choose' => false,
    ];

    return $sizes;
});
