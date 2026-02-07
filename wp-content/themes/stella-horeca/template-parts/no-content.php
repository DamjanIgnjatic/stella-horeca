<div class="col-md-6 content d-flex align-items-center justify-content-center">
    <div class="content text-center">
        <h4 class="h1 text-center"><?php _e('There are no posts', 'stellahoreca'); ?></h2>
            <p class="text"><?php _e('Sorry, nothing to display.', 'stellahoreca'); ?></p>
            <p class="text search-label"><?php _e('Try to search for things you looking for', 'stellahoreca'); ?></p>
            <div class="no-content-search">
                <?php get_template_part('searchform'); ?>
            </div>
            <!-- <a href="<?php echo home_url(); ?>" class="btn go-home"><?php _e('Go Home', 'stellahoreca'); ?></a> -->
    </div>
</div>
<div class="col-md-6 sidebar pl-md-4 d-flex vertical-align-center">
    <img src="<?php bloginfo('template_url'); ?>/img/pages/optimized/500.svg" width="450" height="450" alt="<?php _e('Not Found', 'stellahoreca'); ?>" class="main-image">
</div>