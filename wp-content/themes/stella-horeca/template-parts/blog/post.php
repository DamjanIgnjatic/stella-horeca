<div class="col-md-6 col-xl-4">
    <article class="post post-<?php the_ID(); ?>">
        <!-- post thumbnail -->
        <?php if (has_post_thumbnail()) : // Check if thumbnail exists ?>
            <div class="image-container">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <?php the_post_thumbnail('service', ['class' => 'img-rounded']); ?>
                </a>
            </div>
        <?php endif; ?>
        <!-- /post thumbnail -->

        <!-- post details -->
        <div class="post-body">
            <h2 class="h4">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="link">
                    <?php do_action('theme_excerpt', get_the_title(), 16); ?>
                </a>
            </h2>
            <div class="post-details">
                <div class="detail post-categories"><?php the_category(' '); ?></div>
                <div class="detail post-date"><i class="fa-regular fa-hourglass"></i> <?php the_time('F j, Y'); ?></div>
                <div class="detail reading-time-block"><?php do_action('reading_time', get_the_content()); ?></div>
                <?php do_action('page_views', get_the_ID(), false); ?>
            </div>
            <div class="text">
                <?php do_action('theme_excerpt', get_the_content(), 18); ?>
            </div>
            <?php edit_post_link(); ?>
        </div>
        <!-- /post details -->
    </article>
</div>