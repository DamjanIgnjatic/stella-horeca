<?php get_header(); ?>
 
	<main role="main">
		<?php set_query_var('title', '#'.single_term_title('', false)); ?>
		<?php get_template_part('template-parts/title', 'Title'); ?>

		<!-- Main Content Section -->
		<section class="section main-content-section">
			<div class="container">

				<?php if (have_posts()): ?>
				<div class="row">
					<div class="col-md-8 content">
						<section class="article-section">
							<?php get_template_part('search-loop'); ?>
							<?php get_template_part('template-parts/pagination', 'Pagination'); ?>
						</section>
					</div>
					<div class="col-md-4 sidebar pl-md-4">
						<?php get_sidebar(); ?>
					</div>
				</div>
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