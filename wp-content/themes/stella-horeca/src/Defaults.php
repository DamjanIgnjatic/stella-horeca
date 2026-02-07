<?php

/**
 * use BoldizArt\WpTheme\Defaults;
 */

namespace BoldizArt\WpTheme;

class Defaults
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('init', [$this, 'init']);

            // Add custom HTML content to head
            \add_action('wp_head', [$this, 'addCustomHeader']);

            // Theme setup
            \add_action('after_setup_theme', [$this, 'themeSetup']);

            // Set content width
            \add_action('after_setup_theme', [$this, 'setContentWidth'], 0);

            // Redirect non-admin users to the home page
            \add_action('admin_init', [$this, 'redirectNonAdminUser']);

            // Theme dependencies
            \add_action('admin_notices', [$this, 'themeDependencies']);

            // Add theme excerpt action
            \add_action('theme_excerpt', [$this, 'themeExcerpt'], 10, 3);
        }

        // Add / remove filters
        if (function_exists('add_filter') && function_exists('remove_filter')) {

            // Add sulug to body class
            \add_filter('body_class', [$this, 'addSlugToTheBodyClass']);

            // Remove categor relations from category list
            \add_filter('the_category', [$this, 'removeCategoryRelFromCategoryList']);

            // Chage date format
            \add_filter('the_time', [$this, 'changeDateFormat']);

            // Remove <p> tags from Excerpt
            \remove_filter('the_excerpt', [$this, 'wpautop']);

            // Prevent auto update
            add_filter('site_transient_update_plugins', [$this, 'preventAutoUpdate']);
        }
    }

    /**
     * WprdPress init function
     */
    public function init()
    {
        /**
         * Create test page on theme activation
         */
        if (isset($_GET['activated']) && is_admin()) {
            $title = 'Sample HTML page';

            // Fetch all pages with this title
            $args = [
                'post_type' => 'page',
                'title' => $title
            ];
            $query = new \WP_Query($args);

            if (!$query->have_posts()) {
                // Add page template, for example template-custom.php. Leave blank if you don't want a custom page template
                $template = '';

                // Load template content
                ob_start();
                get_template_part('template-parts/base/sample', 'Sample page');
                $content = ob_get_contents();
                ob_end_clean();

                // Set post data
                $data = [
                    'post_type' => 'page',
                    'post_title' => $title,
                    'post_content' => $content,
                    'post_status' => 'draft',
                    'post_author' => 1
                ];

                // Create a page
                $pid = wp_insert_post($data);

                // Set a custom template
                if ($pid && !empty($template)) {
                    update_post_meta($pid, '_wp_page_template', $template);
                }
            }
        }
    }

    /**
     * Add custom header content
     */
    public function addCustomHeader()
    {
        // Add Google fonts
        if (function_exists('get_field') && $chc = get_field('custom_header_content', 'option')) {
            echo $chc;
        }
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features
     */
    public function themeSetup()
    {
        if (function_exists('add_action')) {

            // Make theme available for translation.
            load_theme_textdomain('stellahoreca', get_template_directory() . '/languages');

            // Add default posts and comments RSS feed links to head.
            add_theme_support('automatic-feed-links');

            // Let WordPress manage the document title
            add_theme_support('title-tag');

            // Add menu support
            add_theme_support('menus');

            // This theme uses wp_nav_menu() in one location.
            register_nav_menus([
                'header-menu' => esc_html__('Primary', 'stellahoreca'),
            ]);

            // Switch default core markup for search form, comment form, and comments to output valid HTML5.
            add_theme_support('html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]);

            // Add theme support for selective refresh for widgets.
            add_theme_support('customize-selective-refresh-widgets');
        }
    }

    /**
     * Remove invalid rel attribute values in the categorylist
     */
    function removeCategoryRelFromCategoryList($list)
    {
        return str_replace('rel="category tag"', 'rel="tag"', $list);
    }

    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     * @global int $content_width
     */
    public function setContentWidth()
    {
        $GLOBALS['content_width'] = apply_filters('setContentWidth', 640);
    }

    /**
     * Add page slug to body class, love this - Credit: Starkers Wordpress Theme
     */
    public function addSlugToTheBodyClass($classes)
    {
        global $post;
        if (is_home()) {
            $key = array_search('blog', $classes);
            if ($key > -1) {
                unset($classes[$key]);
            }
        } elseif (is_page()) {
            $classes[] = sanitize_html_class($post->post_name);
        } elseif (is_singular()) {
            $classes[] = sanitize_html_class($post->post_name);
        }

        return $classes;
    }

    /**
     * Redirect not admin users from the admin pages
     */
    public function redirectNonAdminUser()
    {
        if (!is_user_logged_in() || !current_user_can('administrator')) {
            wp_safe_redirect(site_url());
            exit;
        }
    }

    /**
     * Set theme dependencies
     * @todo Add required plugins
     */
    function themeDependencies()
    {
        if (!function_exists('get_field')) {
            echo '<div class="error"><p>' . __('Warning: The theme needs the Custom Fields pro plugin to function', 'stellahoreca') . '</p></div>';
        }
    }

    /**
     * Change the date format
     */
    function changeDateFormat()
    {
        return get_the_time('j. F');
    }

    /**
     * Create the Custom Excerpts callback
     * @param string $content 
     * @param int $length
     * @param string $more
     */
    function themeExcerpt(string $content, int $length = 14, string $more = '...')
    {
        $content = apply_filters('wptexturize', $content);
        $content = apply_filters('convert_chars', $content);
        $content = wp_trim_words($content, $length, $more);

        echo $content;
    }

    /**
     * Hide the update message for specific plugin
     */
    public function preventAutoUpdate($value)
    {
        if (is_object($value) && isset($value->response) && is_array($value->response)) {

            // List of plugins to disable updateing
            $list = [
                'advanced-custom-fields-pro/acf.php',
                'all-in-one-wp-migration/all-in-one-wp-migration.php',
                'all-in-one-wp-migration-url-extension/all-in-one-wp-migration-url-extension.php',
                'formidable/formidable.php',
                'formidable-pro/formidable-pro.php',
                'formidable-mailchimp/formidable-mailchimp.php'
            ];

            // Disable the plugin update
            foreach ($list as $item) {
                if (array_key_exists($item, $value->response)) {
                    unset($value->response[$item]);
                }
            }
        }

        return $value;
    }
}
