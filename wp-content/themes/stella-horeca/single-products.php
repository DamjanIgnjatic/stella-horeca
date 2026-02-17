<?php get_header(); ?>
<main role="main">
    <div class="blocks">
        <?php get_template_part('template-parts/products/hero') ?>
        <?php the_content() ?>
        <?php get_template_part('template-parts/products/recommended') ?>
    </div>
</main>
<?php get_footer(); ?>