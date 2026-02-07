<?php
/**
 * use BoldizArt\WpTheme\Search;
 */
namespace BoldizArt\WpTheme;

class Search
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add filters
        if (function_exists('add_filter')) {

            // Pre get post filter
            add_filter('pre_get_posts', [$this, 'searchFilter']);
        }
    }

    /**
     * Set up the search that searching only posts and portfolio items
     */
    function searchFilter($query) {
        if ($query->is_search && !is_admin() ) {
            $query->set('post_type', array('post', 'projects'));
        }

        return $query;
    }
}
