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
            'name' => _x('Portfolio', 'Post Type General Name', 'startertheme'),
            'singular_name' => _x('Portfolio', 'Post Type Singular Name', 'startertheme'),
            'menu_name' => __('Portfolio', 'startertheme'),
            'parent_item_colon' => __('Parent portfolio item', 'startertheme'),
            'all_items' => __('All items', 'startertheme'),
            'view_item' => __('View portfolio item', 'startertheme'),
            'add_new_item' => __('Add new item', 'startertheme'),
            'add_new' => __('Add new', 'startertheme'),
            'edit_item' => __('Edit portfolio item', 'startertheme'),
            'update_item' => __('Update portfolio item', 'startertheme'),
            'search_items' => __('Search portfolio item', 'startertheme'),
            'not_found' => __('Not hound', 'startertheme'),
            'not_found_in_trash'  => __('Not found in trash', 'startertheme'),
        ];
            
        // Set other options for Custom Post Type 
        $args = [
            'label' => __('Portfolio', 'startertheme'),
            'description' => __('Website portfolio', 'startertheme'),
            'labels' => $labels,
            'supports' => [
                'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'taxonomies'
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
        register_post_type('portfolio', $args);
        
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
            'search_items' => __('Search projects', 'startertheme'),
            'all_items' => __('All projects', 'startertheme'),
            'parent_item' => __('Parent project', 'startertheme'),
            'parent_item_colon' => __('Parent project:', 'startertheme'),
            'edit_item' => __('Edit project', 'startertheme'), 
            'update_item' => __('Update project', 'startertheme'),
            'add_new_item' => __('Add new project', 'startertheme'),
            'new_item_name' => __('New project', 'startertheme'),
            'menu_name' => __('Projects', 'startertheme'),
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
        register_taxonomy('projects', ['portfolio'], $args);
        register_taxonomy_for_object_type('projects', 'portfolio');
    }
}
