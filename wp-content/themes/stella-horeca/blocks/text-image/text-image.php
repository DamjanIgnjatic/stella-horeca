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
    $description = get_field('description') ?: false;
    $image_left = get_field('image_left') ?: false;
    $image_right = get_field('image_right') ?: false;
    ?>
    <section class="section section-text-image block <?php echo $className; ?>">
        <div class="section-text-image-left"
            style="background-image: url('<?php echo $image_left["url"]; ?>');">

            <div>
                <?php if ($title) : ?>
                    <h2><?php echo $title ?></h2>
                <?php endif; ?>
                <?php if ($description) : ?>
                    <?php echo $description ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($image_right) : ?>
            <div class="section-text-image-right">
                <img src="<?php echo esc_url($image_right['url']); ?>" alt="<?php echo esc_attr($image_right['alt']); ?>" title="<?php echo esc_attr($image_right['alt']); ?>" />
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>