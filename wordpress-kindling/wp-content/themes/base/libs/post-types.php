<?php
/**
 * Theme Post Types.
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

/**
 * Registers theme post types.
 *
 * - This will handle all registration along with adding the post type to the container.
 * - You must use the fully namespaced class name `\Kindling\Theme\Types\Stub::class` not `Stub::class`.
 * - You can access the post type via kindling()->make('type.ClassName')
 */
kindling_post_types_add_types([
    \Kindling\Theme\Types\Stub::class,
    \Kindling\Theme\Types\Post::class,
    \Kindling\Theme\Types\Page::class,
]);
