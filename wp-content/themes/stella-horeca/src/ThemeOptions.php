<?php
/**
 * use BoldizArt\WpTheme\ThemeOptions;
 */
namespace BoldizArt\WpTheme;

class ThemeOptions
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('wp_head', [$this, 'wpHead']);
            \add_action('wp_footer', [$this, 'wpFooter']);
        }
    }

    /**
     * Inject content into the head of the website
     */
    public Function wpHead()
    {
        // Add custom content into the sites header
        if (function_exists('get_field') && $chc = get_field('custom_header_content', 'option')) {
		    echo $chc;
        }
    }

    /**
     * Inject content into the footer of the website
     */
    public Function wpFooter()
    {
        // Add custom content into the sites footer
        if (function_exists('get_field') && $cfc = get_field('custom_footer_content', 'option')) {
		    echo $cfc;
        }
    }
}
