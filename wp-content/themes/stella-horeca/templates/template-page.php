<?php
/* Template Name: Regular page */ 
	get_header();
?>
	<main role="main">
		<!-- Main Content Section -->
		<section class="section block pt-hero main-content-section">
			<div class="container">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<div class="row">
						<div class="col-lg-8 offset-lg-2">
							<h1><?php the_title(); ?></h1>
							<?php the_content(); ?>
						</div>
					</div>
				<?php endwhile; ?>
				<?php else: ?>
					<?php get_template_part('template-parts/no-content', 'No content'); ?>
				<?php endif; ?>
			</div>
		</section>
		<!-- /Main Content Section -->
	</main>
<?php get_footer(); ?>
