<?php
/**
 * use BoldizArt\WpTheme\Assets\Styles;
 */
namespace BoldizArt\WpTheme\Assets;

class Styles
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Add styles
            \add_action('wp_enqueue_scripts', [$this, 'addStyles']);

            // Modify styles
            \add_filter('style_loader_tag', [$this, 'modifyCSS']);

            // Remove styles
            \add_filter('style_loader_tag', [$this, 'removeTypeFromStyleTags']);
        }
    }

    /**
     * Load styles
     */
    public function addStyles()
    {
        if (function_exists('wp_register_style') && function_exists('wp_enqueue_style') && function_exists('get_template_directory_uri')) {
            \wp_register_style('startertheme-theme-style', \get_template_directory_uri() . '/dist/css/theme.css', [], ASSETS_VERSION, 'all');
            \wp_enqueue_style('startertheme-theme-style');

            // Preload the hero section style
            $heroStyle = \get_template_directory_uri() . '/dist/css/theme-hero.css';
            \wp_register_style('preload-startertheme-theme-hero', $heroStyle, [], ASSETS_VERSION, 'all');
            \wp_enqueue_style('preload-startertheme-theme-hero');
            \wp_register_style('startertheme-theme-hero', $heroStyle, [], ASSETS_VERSION, 'all');
            \wp_enqueue_style('startertheme-theme-hero');
        }
    }

    /**
     * Modify stylesheets before rendering
     * Remove Woocommerce styles where they are not necessary
     * @param string $tag
     */
    public function modifyCSS($tag)
    {
        // Preload the important CSS links
        if (strpos($tag, "id='preload-")) {
            $tag = preg_replace("/rel='stylesheet'/", 'rel="preload" as="style"', $tag);
        }

        // Don't change anything else for admin users
        if (!is_admin()) {
            // Remove Woocommerce styles from non-woocommerce pages
            if (
                (strpos($tag, "id='wc-") || strpos($tag, "id='woocommerce-")) &&
                (
                    (function_exists('is_woocommerce') && !is_woocommerce()) || 
                    (function_exists('is_cart') && !is_cart()) || 
                    (function_exists('is_checkout') && !is_checkout()) || 
                    (function_exists('is_account_page') && !is_account_page())
                )
            ) {
                $tag = '';
            }
            
            // Remove jQuery
            if (strpos($tag, "id='jquery")) {
                $tag = '';
            }
        }

        return $tag;
    }

    /**
     * Remove 'text/css' from enqueued stylesheets
     */
    public function removeTypeFromStyleTags($tag)
    {
        return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
    }
}
