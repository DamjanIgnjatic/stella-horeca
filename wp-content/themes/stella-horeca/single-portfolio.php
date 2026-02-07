<?php get_header(); ?>
	<main role="main">
		<div class="blocks">
			<?php if (have_posts()): ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php get_template_part('template-parts/no-content', 'No content'); ?>
			<?php endif; ?>
		</div>
	</main>
<?php get_footer(); ?>
