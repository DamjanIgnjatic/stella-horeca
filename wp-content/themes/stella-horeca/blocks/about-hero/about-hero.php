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
    $image = get_field("image") ?: false;
    $left_description = get_field("left_column_description") ?: false;
    $right_description = get_field("right_column_description") ?: false;
    $title_bottom = get_field("title_bottom") ?: false;
    ?>
    <section class="section section-about-hero block <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <div class="section-about-hero--text-image">
                <div class="description">
                    <?php if ($description) : ?>
                        <?php echo $description ?>
                    <?php endif; ?>
                </div>
                <?php if ($image) : ?>
                    <div class="image">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" title="<?php echo esc_attr($image['alt']); ?>" />
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-about-hero--text-text">
                <?php if ($left_description) : ?>
                    <div>
                        <?php echo $left_description ?>
                    </div>
                <?php endif; ?>
                <?php if ($right_description) : ?>
                    <div>
                        <?php echo $right_description ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($title_bottom) : ?>
                <?php echo $title_bottom ?>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>