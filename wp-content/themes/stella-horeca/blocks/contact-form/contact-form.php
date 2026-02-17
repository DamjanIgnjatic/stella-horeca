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
    $form = get_field('form') ?: false;
    ?>
    <section class="section section-contact-form block animated fade-in <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <?php if ($title) : ?>
                <div class="section-contact-form--title">
                    <h2><?php echo $title ?></h2>
                </div>
            <?php endif; ?>

            <?php if ($form) : ?>
                <?php echo $form ?>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>