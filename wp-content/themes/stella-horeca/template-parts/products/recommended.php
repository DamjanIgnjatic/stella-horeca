<?php
$current_id = get_the_ID();

if (is_singular(get_post_type())) {

    $args = [
        'post_type'      => get_post_type(),
        'posts_per_page' => 2,
        'post__not_in'   => [$current_id],
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
} else {

    $args = [
        'post_type'      => get_post_type(),
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
}

$related = new WP_Query($args);


if ($related->have_posts()) : ?>
    <section class="section section-recommended <?php is_front_page() ? "all-products" : "" ?>">
        <?php while ($related->have_posts()) : $related->the_post();
            $description = get_field('description');
        ?>
            <a class="section-recommended--product" href="<?php the_permalink(); ?>">
                <div class="content">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="section-recommended--product---title">
                            <?php the_post_thumbnail('medium'); ?>
                            <h3><?php the_title(); ?></h3>
                        </div>
                    <?php endif; ?>
                    <div class="section-recommended--product---description">
                        <p><?php echo $description ?></p>
                        <p class="learn-more">Saznaj više</p>
                    </div>
                </div>
            </a>

        <?php endwhile; ?>
    </section>
<?php endif;
wp_reset_postdata();
?>