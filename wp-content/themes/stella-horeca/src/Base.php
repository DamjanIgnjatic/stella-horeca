<?php
/**
 * use BoldizArt\WpTheme\Base;
 */
namespace BoldizArt\WpTheme;

class Base
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
        }
    }

    /**
     * WprdPress init function
     */
    public Function init()
    {

    }
}
