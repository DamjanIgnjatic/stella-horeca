<?php
/**
 * use BoldizArt\WpTheme\Assets\Scripts;
 */
namespace BoldizArt\WpTheme\Assets;

use BoldizArt\WpTheme\WoocommerceHelper;

class Scripts
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Add scripts
            \add_action('wp_enqueue_scripts', [$this, 'addScripts']);

            // Modify scripts
            \add_filter('script_loader_tag', [$this, 'modifyScripts']);

            // Remove jQuery
            \add_action('wp_enqueue_scripts', [$this, 'removejQuery']);
            \add_action('wp_default_scripts', [$this, 'dequeuejQueryMigrate']);
        }
    }

    /**
     * Add scripts
     */
    public function addScripts()
    {
        if (function_exists('wp_register_script') && function_exists('wp_enqueue_script') && function_exists('get_template_directory_uri')) {
            // Default scripts
            \wp_register_script('startertheme-theme', \get_template_directory_uri() . '/dist/js/theme.js', [], ASSETS_VERSION);
            \wp_enqueue_script('startertheme-theme');

            // Theme switcher
            \wp_register_script('startertheme-theme-switcher', \get_template_directory_uri() . '/dist/js/theme-switcher.js', [], ASSETS_VERSION);
            \wp_enqueue_script('startertheme-theme-switcher');

            // Fontawesome script
            \wp_register_script('fa-fontawesome-script', 'https://use.fontawesome.com/releases/v5.15.4/js/all.js', [], '5.15.4');
            \wp_enqueue_script('fa-fontawesome-script');

            // Dynamic modals
            \wp_register_script('dynamic-modals-script', \get_template_directory_uri() . '/dist/js/dynamic-modals.js', [], ASSETS_VERSION);
            \wp_enqueue_script('dynamic-modals-script');
        }
    }

    /**
     * Load a script files asynchronously
     * Remove some scripts by defined rules
     * @param string $tag
     */
    function modifyScripts($tag)
    {
        // Load async some script tags
        if (strpos($tag, "id='async-")) {
            $tag = preg_replace("/type='text\/javascript'/", "async defer type='text/javascript'", $tag);
        }

        // Don't change anything else for admin users
        if (!is_customize_preview() && !is_admin()) {

            // Remove woocommerce scripts from non-wc pages
            if (!WoocommerceHelper::isWooPage() && (strpos($tag, "id='jquery-blockui-js") || strpos($tag, "id='woocommerce-") || strpos($tag, "id='wc-") || strpos($tag, "id='js-cookie-js"))) {
                $tag = '';
            }

            // Remove non-necessary scripts
            if (strpos($tag, "id='lodash-") || strpos($tag, "id='current-template-js-js") || strpos($tag, "id='pickr-js")) {
                $tag = '';
            }

            $tag = str_replace('></script>', ' defer></script>', $tag);
        }

        return $tag;
    }

    /**
     * Completely Remove jQuery from WordPress in specific cases
     */
    function removejQuery()
    {
        if (
            !is_customize_preview() && 
            !is_admin() && 
            $GLOBALS['pagenow'] != 'wp-login.php' &&
            !WoocommerceHelper::isWooPage() &&
            !is_page_template(['template-price-calculator.php', 'archive-product.php'])
        ) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', '', '', '', true);
        }
    }

    /**
     * Prevent jQuery scripts
     * @param Object $scripts
     */
    function dequeuejQueryMigrate($scripts) {
        if (
            !is_admin() && 
            !is_customize_preview() && 
            !empty($scripts->registered['jquery']) && 
            !WoocommerceHelper::isWooPage()
        ) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                ['jquery-migrate']
            );
        }
    }
}
