<?php get_header(); ?>
	<main role="main">
		<!-- Main Content Section -->
		<section class="section section block main-content-section">
			<div class="container">
				<?php set_query_var('title', single_term_title('', false)); ?>
				<?php get_template_part('template-parts/title', 'Title'); ?>

				<?php the_archive_description( '<div class="category-description">', '</div>' ); ?>

				<?php if (have_posts()): ?>
					<section class="article-section posts">
						<div class="row g-4">
							<?php while(have_posts()) : the_post(); ?>
								<?php get_template_part('template-parts/blog/post', 'Post'); ?>
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
