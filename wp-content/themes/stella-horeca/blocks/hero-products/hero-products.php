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
    ?>
    <section class="section section-product-hero block <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <d iv class="section-product-hero--wrapper animated left-to-right">
                <?php if ($title): ?>
                    <h1><?php echo $title; ?><span>.</span></h1>
                <?php endif; ?>
                <?php if ($description): ?>
                    <?php echo $description; ?>
                <?php endif; ?>
            </d>
        </div>
        </div>
    </section>
<?php endif; ?>