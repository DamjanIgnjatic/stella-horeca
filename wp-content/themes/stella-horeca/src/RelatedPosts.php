<?php
/**
 * use BoldizArt\WpTheme\RelatedPosts;
 */
namespace BoldizArt\WpTheme;

class RelatedPosts
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add shortcodes
        if (function_exists('add_shortcode')) {

            // Register shortcode
            \add_shortcode('related_product', [$this, 'registerRelatedPostsShortcode']);
            \add_shortcode('page_list', [$this, 'listPagesShortcode']);
        }
    }

    /**
     * Returns the template part for related posts
     */
    function relatedPostsTemplate()
    {
        ?>
        <div class="nop">
            <div class="related-post">
                <div class="middle d-flex justify-content-center align-items-center">
                    <div class="content">
                        <h3 class="text-white"><?php echo wp_trim_words(get_the_title(), 5); ?></h3>
                        <span class="categories">
                            <?php the_category(' | ');  ?>
                        </span>
                    </div>
                </div>
                <!-- post thumbnail -->
                <?php if (has_post_thumbnail()) : // Check if Thumbnail exists ?>
                    <?php the_post_thumbnail('post-thumb', ['width' => 420, 'height' => 260]); // Fullsize image for the single post ?>
                <?php endif; ?>
                <!-- /post thumbnail -->
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="link"></a>
            </div>
        </div>
        <?php
    }

    /**
     * Returns the template part for inline related post
     */
    function inlineRelatedPostsTemplate()
    {
        return '<h4 class="h2">' . __('Related', 'startertheme') . ': <a class="post-title" href="' . get_permalink() . '" alt="' . get_the_title() . '">' . get_the_title() . '</a>';
    }

    /**
     * Return similar posts by post id
     * @param int $pid
     * @param int $count
     */
    function relatedPosts($pid, $count = 3) {
        // Get the post categories
        $categories = get_the_terms($pid, 'category');
        if (!$categories || is_wp_error($categories))
            return;
            
        $cids = [];
        foreach ($categories as $category) {
            $cids[] = $category->term_id;
        }
        
        // Get the post tags
        $tags = get_the_terms($pid, 'post_tag');
        if (!$tags || is_wp_error($tags))
            return;

        $tids = [];
        foreach ($tags as $tag) {
            $tids[] = $tag->term_id;
        }    

        // Set the query args
        $args = [
            'post_type' => ['post', 'project'],
            'lang' => pll_current_language(),
            'orderby' => 'rand',
            'posts_per_page' => $count,
            'post__not_in' => [$pid],
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $cids,
                ],
                [
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $tids,
                    'operator' => 'IN',
                ],
            ]
        ];
        
        $brtQuery = new \WP_Query($args);
        if ($brtQuery->have_posts()) {
            if ($count > 1) {
                ?>
                <div class="related-posts-container">
                    <h2 class="h2"><?php _e('Related posts', 'startertheme'); ?></h2>
                    <div class="related-posts d-sm-flex justify-content-between">
                    <?php
                    while ($brtQuery->have_posts()):
                        $brtQuery->the_post();
                        $this->relatedPostsTemplate();
                        wp_reset_postdata();
                    endwhile;
                    ?>
                    </div>
                </div>
                <?php 
            } else {
                while ($brtQuery->have_posts()) {
                    $brtQuery->the_post();
                    $irp = '<div class="inline-related-post">' . $this->inlineRelatedPostsTemplate() . '</div>';
                    wp_reset_postdata();                
                    return $irp;
                }
            }
        }
        wp_reset_query();
    }

    /**
     * @use [related_product] to return related posts
     * @param array $args
     */
    function registerRelatedPostsShortcode($args) {
        $count = array_key_exists('count', $args) ? $args['count'] : null;

        return $this->relatedPosts($args['id'], $count);
    }

    /**
     * @use [page_list] to list all pages
     */
    function listPagesShortcode()
    {
        return json_encode([\wp_list_pages()]);
    }
}
