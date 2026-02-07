<div class="row mb-4">
    <div class="col-md-6 order-last order-md-first text-content">
        <h1><?php the_title(); ?></h1>
        <?php if ($shortDescription = get_field('short_description')): ?>
            <div class="text"><?php echo $shortDescription; ?></div>
        <?php endif; ?>
        <?php if ($button = get_field('button_link')): ?>
            <a href="<?php echo $button['url']; ?>" class="button btn btn-primary" target="<?php echo $button['target']; ?>">
                <?php echo array_key_exists('title', $button) && !empty($button['title']) ? $button['title'] : __('Read more', 'startertheme'); ?>
            </a>
        <?php endif; ?>
    </div>
    <div class="col-md-6 d-flex justify-content-center align-items-center main-image-container">
        <?php 
            if ($postImageId = get_post_thumbnail_id($post->ID)) {
                echo '<link rel=preload href="'.wp_get_attachment_image_url($postImageId, 'services').'" as="image">';
                echo wp_get_attachment_image($postImageId, 'services', false, ['class' => 'hero-image']);
            }
        ?>
    </div>
</div>
