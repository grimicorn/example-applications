<?php
/**
 * Post Type: Stub
 *
 * @package Kindling_Post_Types
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

namespace Kindling\Theme\Types;

use Kindling\PostTypes\Base as BaseType;

/**
 * @codingStandardsIgnoreStart
 * If this is your first time creating a post type this way please read through all comments before continuing.
 *
 * - Change all references to [stub/Stub] to be your post type name.
 * - You can configure/see default post type arguments in the `arguments()` method or remove it if you do not need to change the defaults.
 * - You can configure/see default taxonomy arguments in the `taxonomyArguments()` method or remove it if you do not need to change the defaults.
 * - You can configure/see configuration in __construct().
 * - Feel free to delete this comment once complete.
 * - Any commented methods/properties feel free to remove if not using.
 * - Once complete add the fully namespaced class Kindling\PostTypes\Type\{name}::class to libs/post-types.php to register the post type.
 *
 * Please do take a look at Base along with reading all comments to get more of an understanding of what is going on.
 *
 * @codingStandardsIgnoreEnd
 */

/**
 * Stub post type.
 */
class Stub extends BaseType
{
    /**
     * Creates the Stub post type.
     */
    public function __construct()
    {
        // As long as you follow the convention of lowercase/hyphenated singular type names.
        // Along with simple names you do not need to pass the $pluralTitle or $singularTitle.
        // If you need to override the defaults you can see \Kindling\PostTypes\Base::__construct
        parent::__construct('stub');

        // Set taxonomy defaults to "{type}-categories"
        // $this->setTaxonomy("{$this->type}-categories");

        // Set the taxonomy to null to disable it
        // $this->setTaxonomy(null);

        // Disable post table image column.
        // Automatically disables if featured image support is disabled.
        // $this->setDisableImageColumn(true);
    }

    /**
     * The post type arguments.
     * You can remove this method if you do not need to alter the defaults.
     *
     * @return array
     */
    public function arguments()
    {
        // Lowercase Labels
        $lcPluralTitle = strtolower($this->pluralTitle);
        $lcSingularTitle = strtolower($this->singularTitle);

        return [
            'labels' => [
                'name' => __($this->pluralTitle),
                'singular_name' => __($this->singularTitle),
                'add_new' => __("Add New {$this->singularTitle}"),
                'add_new_item' => __("Add News {$this->singularTitle}"),
                'edit_item' => __("Edit {$this->singularTitle}"),
                'new_item' => __("New {$this->singularTitle}"),
                'all_items' => __("All {$this->pluralTitle}"),
                'view_item' => __("View {$this->singularTitle}"),
                'search_items' => __("Search {$this->pluralTitle}"),
                'not_found' => __("No {$lcPluralTitle} found"),
                'not_found_in_trash' => __("No {$lcPluralTitle} found in Trash"),
                'parent_item_colon' => __(''),
                'menu_name' => __($this->pluralTitle),
                'featured_image' => __("{$this->singularTitle} Image"),
                'set_featured_image' => __("Set {$lcSingularTitle} image"),
                'remove_featured_image' => __("Remove {$lcSingularTitle} image"),
                'use_featured_image' => __("Use as {$lcSingularTitle} image"),
                'view_items' => "View {$this->pluralTitle}",
                'archives' => "All {$this->pluralTitle}",
                'attributes' => "{$this->singularTitle} Attributes",
                'insert_into_item' => "Insert into {$lcSingularTitle}",
                'uploaded_to_this_item' => "Uploaded to this {$lcSingularTitle}",
                'filter_items_list' => "Filter {$lcPluralTitle} list",
                'items_list_navigation' => "{$this->pluralTitle} list navigation",
                'items_list' => "{$this->pluralTitle} list",
                'name_admin_bar' => $this->singularTitle,
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => "{$this->type}s",
                'with_front' => false
            ],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => true,
            'menu_position' => 5,
            'can_export' => true,
            'supports' => [
                'title',
                'editor',
                'post-thumbnails',
                'custom-fields',
                'page-attributes',
                'author',
                'thumbnail',
                'excerpt',
                'trackbacks',
                'comments',
                'revisions',
                'post-formats',
            ],
            'menu_icon' => 'dashicons-edit',
        ];
    }

    /**
     * The taxonomy arguments.
     * You can remove this method if you do not need to alter the defaults.
     *
     * @return array
     */
    public function taxonomyArguments()
    {
        $pluralTitle = "{$this->singularTitle} Categories";
        $singularTitle = "{$this->singularTitle} Category";

        return [
            'hierarchical' => true,
            'labels' => [
                'name' => _x("{$pluralTitle}", 'taxonomy general name', 'taxonomy general name'),
                'singular_name' => _x("{$singularTitle}", 'taxonomy singular name', 'taxonomy singular name'),
                'search_items' => __("Search {$pluralTitle}"),
                'all_items' => __("All {$pluralTitle}"),
                'parent_item' => __("Parent {$singularTitle}"),
                'parent_item_colon' => __("Parent {$singularTitle}:"),
                'edit_item' => __("Edit {$singularTitle}"),
                'update_item' => __("Update {$singularTitle}"),
                'add_new_item' => __("Add New {$singularTitle}"),
                'new_item_name' => __("New {$singularTitle} Name"),
                'menu_name' => __("{$pluralTitle}"),
                'view_item' => __("View {$singularTitle}"),
                'popular_items' => __("Popular {$pluralTitle}"),
                'separate_items_with_commas' => __("Separate {$pluralTitle} with commas"),
                'add_or_remove_items' => __("Add or remove {$pluralTitle}"),
                'choose_from_most_used' => __("Choose from the most used {$pluralTitle}"),
                'not_found' => __("No {$pluralTitle} found."),
                'no_terms' => "No {$pluralTitle}",
                'items_list_navigation' => "{$pluralTitle} list navigation",
                'items_list' => "{$pluralTitle} list",
                'most_used' => "Most Used",
                'back_to_items' => "&larr; Back to {$pluralTitle}",
                'name_admin_bar' => "Other {$singularTitle}",
                'archives' => "All Other {$pluralTitle}",
            ],
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'query_var' => $this->taxonomy,
            'rewrite' => [
                'slug' => "{$this->type}-categories",
                'with_front' => false,
                'hierarchical' => true,
            ],
        ];
    }
}
