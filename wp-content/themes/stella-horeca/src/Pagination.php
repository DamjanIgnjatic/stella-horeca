<?php
/**
 * use BoldizArt\WpTheme\Pagination;
 */
namespace BoldizArt\WpTheme;

class Pagination
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('pagination', [$this, 'pagination']);
            \add_action('single_pagination', [$this, 'singlePagination']);
        }
    }

    /**
     * Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
     */
    function pagination()
    {
        global $wp_query;
        $big = 999999999;

        echo paginate_links([
            'base' => str_replace($big, '%#%', get_pagenum_link($big)),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'prev_text' => '&lsaquo;',
            'next_text' => '&rsaquo;'
        ]);
    }

    /**
     * Pagination (prev/next) for single posts and pages
     */
    function singlePagination()
    {
        ?>
        <div class="single-pagination row d-flex justify-content-between py-5">
            <div class="link prev col-6">
                <?php if (get_previous_post_link()): ?>
                <div class="h4 d-block"><?php _e('Previous', 'startertheme'); ?></div>
                <?php previous_post_link('%link'); ?>
                <?php endif; ?>
            </div>
            <div class="link next col-6">
                <?php if (get_next_post_link()): ?>
                <div class="h4 d-block"><?php _e('Next', 'startertheme'); ?></div>
                <?php next_post_link('%link'); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
