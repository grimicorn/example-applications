<?php
/**
 * Theme Actions.
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

/**
 * Theme setup.
 */
add_action('after_setup_theme', function () {
    // Register wp_nav_menu() menus
    // http://codex.wordpress.org/Function_Reference/register_nav_menus
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'kindling'),
        'social_media_menu' => __('Social Media Menu', 'kindling'),
    ]);

    // Enable post formats
    // http://codex.wordpress.org/Post_Formats
    // add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);
});
