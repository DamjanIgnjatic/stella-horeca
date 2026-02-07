<article class="col-12 post post-<?php the_ID(); ?> latest">
    <div class="row">
        <div class="col-lg-5">
        <!-- post thumbnail -->
        <?php if (has_post_thumbnail()) : // Check if thumbnail exists ?>
            <div class="image-container mb-3">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <?php the_post_thumbnail('service', ['class' => 'img-rounded']); ?>
                </a>
            </div>
        <?php endif; ?>
        <!-- /post thumbnail -->
        </div>
        <div class="col-lg-7">
            <!-- post details -->
            <div class="post-body">
                <h1 class="h2 mt-0">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="link">
                        <?php the_title(); ?>
                    </a>
                </h1>
                <div class="post-details d-flex align-items-center">
                    <div class="detail post-categories m-0 pe-2"><?php echo getCustomPost('projects', ' '); ?></div>
                    <div class="detail"><i class="fa-regular fa-hourglass"></i> <?php the_time('j. F'); ?></div>
                    <?php do_action('page_views', get_the_ID(), false); ?>
                </div>
                <div class="text">
                    <?php do_action('theme_excerpt', get_the_excerpt(), 28); ?>
                </div>
            </div>
            <!-- /post details -->

            <?php edit_post_link(); ?>
        </div>
    </div>
    <hr class="mt-4">
</article>
