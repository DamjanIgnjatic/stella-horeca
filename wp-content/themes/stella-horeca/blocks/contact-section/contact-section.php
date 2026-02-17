<?php
/* 
* Block Name: Image
* Post Type: page, post
*/
?>
<?php if (isset($block['data']['previewImage'])): ?>
    <?php do_action('block_image', __FILE__); ?>
<?php else: ?>
    <?php
    // Include helpers
    include get_template_directory() . '/template-parts/base/block-helper.php';

    // Load values and assing defaults
    $title = get_field('title') ?: false;
    $link = get_field('link') ?: false;
    ?>
    <section class="section section-contact block animated fade-in <?php echo $className; ?>">
        <div class="container">
            <?php if ($link) :
                $link_url = $link["url"];
                $link_title = $link["title"];
                $link_target = $link['target'] ? $link['target'] : '_self';

            ?>
                <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo $title ?> <span><?php echo esc_html($link_title); ?></span></a>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>