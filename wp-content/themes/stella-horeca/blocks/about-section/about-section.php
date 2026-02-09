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
    $tagline = get_field('tagline') ?: false;
    $title = get_field('title') ?: false;
    $description = get_field('description') ?: false;

    ?>
    <section class="section section-about block <?php echo $className; ?>">
        <div class="container">
            <?php if ($tagline) : ?>
                <span><?php echo $tagline ?></span>
            <?php endif; ?>
            <?php if ($title) : ?>
                <?php echo $title ?>
            <?php endif; ?>
            <?php if ($description) : ?>
                <p><?php echo $description ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>