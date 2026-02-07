<?php get_header(); ?>

	<main role="main">
		<section class="section section block main-content-section">
			<div class="container">
				<h1 class="h2">
					<?php _e('Search results for', 'startertheme'); ?> <span class="text-primary"><?php echo get_search_query(); ?></span> (<?php echo $wp_query->found_posts; ?>)
				</h1>
				<?php if (have_posts()): ?>
					<section class="article-section posts">
						<div class="row g-4">
							<?php while(have_posts()) : the_post(); ?>
								<?php 
									switch (get_post_type()) {
										case 'projects':
										case 'portfolio':
											get_template_part('template-parts/portfolio/portfolio', 'Portfolio');
											break;
										
										default:
											get_template_part('template-parts/blog/post', 'Post');
											break;
									}
								 ?>
							<?php endwhile; ?>
						</div>
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
