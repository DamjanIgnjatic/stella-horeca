<?php
/* Template Name: Container Template */ 
	get_header();
?>
	<main role="main">
		<!-- Main Content Section -->
		<section class="section main-content-section">
			<div class="container">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; ?>
				<?php else: ?>
					<?php get_template_part('template-parts/no-content', 'No content'); ?>
				<?php endif; ?>
			</div>
		</section>
		<!-- /Main Content Section -->
	</main>

<?php get_footer(); ?>
