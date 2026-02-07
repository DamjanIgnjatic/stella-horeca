<div class="row g-4">
	<?php if (have_posts()): ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('template-parts/blog2/post', 'Post'); ?>
		<?php endwhile; ?>
	<?php endif; ?>
</div>
