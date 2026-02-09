<?php

/**
 * use BoldizArt\WpTheme\Base;
 */

namespace BoldizArt\WpTheme;

class CustomPostTypes
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Register custom post types
            \add_action('init', [$this, 'registerCustomPostTypes']);

            // Register custom taxonomies
            \add_action('init', [$this, 'registerCustomTaxonomies']);
        }
    }

    /**
     * Create a new post type
     */
    function registerCustomPostTypes()
    {
        // Set UI labels for Custom Post Type
        $labels = [
            'name' => _x('Products', 'Post Type General Name', 'stellahoreca'),
            'singular_name' => _x('Products', 'Post Type Singular Name', 'stellahoreca'),
            'menu_name' => __('Products', 'stellahoreca'),
            'parent_item_colon' => __('Parent Products item', 'stellahoreca'),
            'all_items' => __('All items', 'stellahoreca'),
            'view_item' => __('View Products item', 'stellahoreca'),
            'add_new_item' => __('Add new item', 'stellahoreca'),
            'add_new' => __('Add new', 'stellahoreca'),
            'edit_item' => __('Edit Products item', 'stellahoreca'),
            'update_item' => __('Update Products item', 'stellahoreca'),
            'search_items' => __('Search Products item', 'stellahoreca'),
            'not_found' => __('Not hound', 'stellahoreca'),
            'not_found_in_trash'  => __('Not found in trash', 'stellahoreca'),
        ];

        // Set other options for Custom Post Type 
        $args = [
            'label' => __('Products', 'stellahoreca'),
            'description' => __('Website Products', 'stellahoreca'),
            'labels' => $labels,
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'author',
                'thumbnail',
                'comments',
                'revisions',
                'custom-fields',
                'taxonomies'
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,

            // This is where we add taxonomies to our CPT
            'taxonomies' => [
                'projects'
            ]
        ];

        // Registering your Custom Post Type
        register_post_type('Products', $args);
    }

    /**
     * Register custom taxonomy
     */
    function registerCustomTaxonomies()
    {
        // Set labels
        $labels = [
            'name' => _x('Projects', 'taxonomy general name'),
            'singular_name' => _x('Project', 'taxonomy singular name'),
            'search_items' => __('Search projects', 'stellahoreca'),
            'all_items' => __('All projects', 'stellahoreca'),
            'parent_item' => __('Parent project', 'stellahoreca'),
            'parent_item_colon' => __('Parent project:', 'stellahoreca'),
            'edit_item' => __('Edit project', 'stellahoreca'),
            'update_item' => __('Update project', 'stellahoreca'),
            'add_new_item' => __('Add new project', 'stellahoreca'),
            'new_item_name' => __('New project', 'stellahoreca'),
            'menu_name' => __('Projects', 'stellahoreca'),
        ];

        // Set args
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'publicly_queryable' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => 'projects'
            ],
            'show_ui' => true,
            'show_in_rest' => true
        ];

        // Register taxonomy
        register_taxonomy('projects', ['Products'], $args);
        register_taxonomy_for_object_type('projects', 'Products');
    }
}
