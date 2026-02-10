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
    ?>
    <section class="section contact-section-wrapper block <?php echo $className; ?>" 
        <?php if ($bgUrl): ?>
            style="background-image: url('<?php echo $bgUrl; ?>');"
            data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
            data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>
        <?php if ($sectionId): ?>
            id="<?php echo $sectionId; ?>"
        <?php endif; ?>
    >
        <div class="container">
            <?php if ($contentWidth): ?>
                <div class="row">
                    <div class="<?php echo $contentWidth; ?>">
            <?php endif; ?>
                <!-- Content START -->
                <?php if ($title): ?>
                    <h2><?php echo $title; ?></h2>
                <?php endif; ?>
                <!-- Content END -->
            <?php if ($contentWidth): ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
