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
    <section class="section section-contact block <?php echo $className; ?>">
        <div class="container">
            <a href="#">Pronadjite najbolje rešenje za vaš prostor! <span>Kontaktirajte nas</span></a>

        </div>
    </section>
<?php endif; ?>