
<?php if (array_key_exists('item', $args) && $item = $args['item']): ?>
    <div class="slider-item col-12 item-<?php echo $item->ID; ?> portfolio">
        <a href="<?php echo get_the_permalink($item->ID); ?>" aria-label="<?php echo get_the_title($item->ID); ?>">
            <div class="image-container">
                <?php 
                    if ($imageId = get_post_thumbnail_id($item->ID)) {
                        echo wp_get_attachment_image($imageId, 'portfolio', false, ['class' => 'portfolio-image']);
                    }
                ?>
            </div>
            <h3 class="h3"><?php echo get_the_title($item->ID); ?></h3>
        </a>
        <div class="text">
            <?php if ($technologoies = get_field('technologies', $item->ID)): ?>
                <p class="technologies"><i class="icon fa fa-code"></i> <span><?php echo implode('</span>,<span>', explode(',', $technologoies)); ?></span></p>
            <?php endif; ?>
        </div>
        <a href="<?php echo get_the_permalink($item->ID); ?>" class="btn btn-silent" title="<?php echo get_the_title($item->ID); ?>" target="_blank"><?php _e('Check', 'startertheme'); ?> <?php echo get_the_title($item->ID); ?></a>
        <?php if ($editLink = get_edit_post_link($item->ID)):?>
            <a href="<?php echo $editLink; ?>" target="_blank"><?php echo __('Edit', 'startertheme'); ?></a>
        <?php endif; ?>
    </div>
<?php endif; ?>
