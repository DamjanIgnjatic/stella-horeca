<?php get_header(); ?>
<?php 
	$term = get_queried_object();
	wp_reset_query();
	$args = [
		'post_type' => 'portfolio',
		'tax_query' => [
			[
				'taxonomy' => 'projects',
				'field' => 'slug',
				'terms' => $term->slug,
			]
		]
	];
	$loop = new WP_Query($args);
?>
	<main role="main">
		<!-- Main Content Section -->
		<section class="section section block main-content-section">
			<div class="container">
				<?php set_query_var('title', single_term_title('', false)); ?>
				<?php get_template_part('template-parts/title', 'Title'); ?>

				<?php the_archive_description( '<div class="category-description">', '</div>' ); ?>

				<?php if ($loop->have_posts()): ?>
					<section class="article-section posts">
						<div class="row">
							<?php while($loop->have_posts()) : $loop->the_post(); ?>
								<?php get_template_part('template-parts/portfolio/portfolio', 'Portfolio'); ?>
							<?php endwhile; ?>
							<?php get_template_part('template-parts/pagination', 'Pagination'); ?>
						</div>
					</section>
				<?php else: ?>
				<div class="row no-content">
					<?php get_template_part('template-parts/no-content', 'No content'); ?>
				</div>
				<?php endif; ?>
				
			</div>
		</section>
		<!-- /Main Content Section -->
	</main>
<?php get_footer(); ?>
