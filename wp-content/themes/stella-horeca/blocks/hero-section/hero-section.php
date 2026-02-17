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
    $link = get_field('link') ?: false;
    ?>
    <section class="section section-hero block <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <div class="section-hero--wrapper animated left-to-right">
                <?php if ($title) : ?>
                    <h1><?php echo $title ?></h1>
                <?php endif; ?>
                <?php if ($description) : ?>
                    <?php echo $description ?>
                <?php endif; ?>

                <?php if ($link) :
                    $link_url = $link["url"];
                    $link_title = $link["title"];
                ?>
                    <a class="horeca-btn" href="<?php echo $link_url ?>"><?php echo $link_title ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>