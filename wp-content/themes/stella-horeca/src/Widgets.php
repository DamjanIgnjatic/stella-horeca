<?php
/**
 * use BoldizArt\WpTheme\Widgets;
 */
namespace BoldizArt\WpTheme;

class Widgets
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
        /**
         * If Dynamic Sidebar Exists
         */
        if (function_exists('register_sidebar')) {
            // Define Right Sidebar
            \register_sidebar([
                'name' => __('Right Sidebar', 'startertheme'),
                'description' => __('Right sidebar widget area', 'startertheme'),
                'id' => 'widget-area-1',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            ]);

            // Define footer widget areas
            $footerWidgetNames = [
                'subscribe-form' => __('Subscribe form', 'startertheme')
            ];
            for ($i=1; $i < 8; $i++) { 
                // $footerWidgetNames["footer-section-[$i]"] = sprintf(__('Footer section %s', 'startertheme'), $i);
                $footerWidgetNames["footer-section-{$i}"] = sprintf(__('Footer section %s', 'startertheme'), $i);
            }

            foreach ($footerWidgetNames as $key => $name) {
                \register_sidebar([
                    'name' => $name,
                    'description' => $name,
                    'id' => $key, 
                    'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3 class="widget-title">',
                    'after_title' => '</h3>',
                ]);
            }
        }
    }
}
