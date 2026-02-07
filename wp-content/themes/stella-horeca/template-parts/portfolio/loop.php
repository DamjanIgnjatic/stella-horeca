<div class="row g-4">
	<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
	<?php $no = 0; if (have_posts()): while (have_posts()) : the_post(); ?>
		<?php if ($paged < 2 &&  !$no): ?>
			<?php get_template_part('template-parts/portfolio/latest-portfolio', 'Latest portfolio item'); ?>
		<?php else: ?>
			<?php get_template_part('template-parts/portfolio/portfolio', 'Portfolio item'); ?>
		<?php endif; ?>
		<?php $no++; ?>
	<?php endwhile; ?>
	<?php endif; ?>
</div>