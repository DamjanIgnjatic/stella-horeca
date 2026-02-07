<?php

/**
 * use BoldizArt\WpTheme\PageViews;
 */

namespace BoldizArt\WpTheme;

class PageViews
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('page_views', [$this, 'pageViews'], 10, 1);
            \add_action('wp_loaded', [$this, 'increasePageViews']);
        }
    }

    /**
     * Pageview counter
     * @param int $pid (post_id)
     */
    function pageViews($pid = null)
    {
        // Check if the current user is logged in as admin
        if (current_user_can('manage_options')) {

            // Check for post id
            if (!$pid) {
                $pid = get_the_ID();
            }

            // Get page views count
            $countKey = 'pageviews_count';
            $count = (int) get_post_meta($pid, $countKey, true);

            echo sprintf(__('Visited: %s', 'stellahoreca'), $count);
        }
    }

    /**
     * Pageview counter
     */
    function increasePageViews()
    {
        // Check if the current user isn't an admin one
        if (!current_user_can('manage_options')) {
            // Get the current page id
            $pid = get_the_ID();

            // Count only singular pages
            if (is_singular() && $pid == get_queried_object_id()) {

                // Get page views count
                $countKey = 'pageviews_count';
                $count = (int) get_post_meta($pid, $countKey, true);

                // Update the post meta
                $count++;
                update_post_meta($pid, $countKey, $count);
            }
        }
    }
}
