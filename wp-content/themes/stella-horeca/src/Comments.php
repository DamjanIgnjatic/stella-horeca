<?php
/**
 * use BoldizArt\WpTheme\Comments;
 */
namespace BoldizArt\WpTheme;

class Comments
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Enable threaded Comments
            \add_action('get_header', [$this, 'enableThreadedComments']);

            // Remove comment style
            \add_action('widgets_init', [$this, 'removeCommentsStyle']);

            // Add comments
            \add_action('theme_comments', [$this, 'themeComments'], 10, 3);
        }
    }

    /**
     * Enable threaded Comments
     */
    public function enableThreadedComments()
    {
        if (!is_admin()) {
            if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
                wp_enqueue_script('comment-reply');
            }
        }
    }

    /**
     * Remove wp_head() injected Recent Comment styles
     */
    function removeCommentsStyle()
    {
        global $wp_widget_factory;
        remove_action('wp_head', array(
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
        ));
    }

    /**
     * Custom Comments Callback
     */
    function themeComments($comment, $args, $depth)
    { 
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
    ?>
        <!-- heads up: starting < for the html tag (li or div) in the next line: -->
        <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
            <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, isset($args['180']) ? $args['180'] : ''); ?>
                <span class="author-name"><?php echo get_comment_author_link(); ?></span>

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'startertheme') ?></em>
                    <br />
                <?php endif; ?>

                <div class="comment-meta commentmetadata">
                    <?php
                        printf( __('%1$s at %2$s', 'startertheme'), get_comment_date(),  get_comment_time()) ?><?php edit_comment_link(__('(Edit)', 'startertheme'),'  ','' );
                    ?>
                </div>

                <?php comment_text() ?>

                <div class="reply">
                <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
        <?php if ( 'div' != $args['style'] ) : ?>
        </div>
        <?php endif; ?>
    <?php 
    }
}
