<div class="row mb-4">
    <div class="row col-md-6 text-content main-image-container">
        <div class="col-12 order-2 order-md-0">
            <h1><?php the_title(); ?></h1>
            <?php if ($shortDescription = get_field('short_description')): ?>
                <p class="text"><?php echo $shortDescription; ?></p>
            <?php endif; ?>
        </div>
        <div class="col-12 text-center">
        <?php 
            if ($articleImageId = get_post_thumbnail_id($post->ID)) {
                echo wp_get_attachment_image($articleImageId, 'services');
            }
        ?>
        </div>
    </div>
    <div class="col-md-6 d-flex justify-content-center align-items-center">
        <?php the_content(); ?>
    </div>
</div>
