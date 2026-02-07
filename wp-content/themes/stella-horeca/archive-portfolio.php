<?php get_header(); ?>
<main role="main">
    <section class="section section block main-content-section">
        <div class="container">
            <?php if (have_posts()): ?>
				<section class="article-section posts">
					<?php get_template_part('template-parts/portfolio/loop', 'Portfolio loop'); ?>
					<?php get_template_part('template-parts/pagination', 'Pagination'); ?>
				</section>
			<?php else: ?>
				<div class="row no-content">
					<?php get_template_part('template-parts/no-content', 'No content'); ?>
				</div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>