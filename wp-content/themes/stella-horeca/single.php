<?php
	// Hide the sidebar
	set_query_var('brand', false);
	get_header(); 
?>

<main role="main">
		<!-- Main Content Section -->
		<section class="section block single-post-section">
			<div class="container py-2">
				<div class="row">
					<div class="col-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2 content">

						<!-- /section -->
						<section class="article-section single-post posts">

							<?php if (have_posts()): while (have_posts()) : the_post(); ?>

								<!-- article -->
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

									<!-- post title -->
									<h1 class="h2 mt-0"><?php the_title(); ?></h1>
									<!-- /post title -->

									<!-- post thumbnail -->
									<?php if (has_post_thumbnail()) : // Check if Thumbnail exists ?>
										<div class="post-image text-center img-bg">
										<?php 
											if ($articleImageId = get_post_thumbnail_id()) {
												echo '<link rel=preload href="'.wp_get_attachment_image_url($articleImageId, 'services').'" as="image">';
												echo wp_get_attachment_image($articleImageId, 'services', false, ['class' => 'mt-0 single-post-image skip-lazy img-rounded']);
											}
										?>
										<div class="img-wrapper"></div>
										</div>
									<?php endif; ?>
									<!-- /post thumbnail -->

									<!-- post details -->
									<div class="post-details pb-3 text-center d-lg-flex justify-content-center align-items-center">
										<div class="detail post-categories px-2 mb-lg-0"><?php the_category(' '); ?></div>
										<div class="detail post-date"><i class="fa-regular fa-hourglass"></i> <?php the_time('F j, Y'); ?></div>
										<div class="detail reading-time-block"><?php do_action('reading_time', get_the_content()); ?></div>
										<div class="detail reading-time-block"><?php do_action('page_views'); ?></div>
										<hr>
									</div>
									<!-- /post details -->

									<!-- Dynamic Content -->
									<?php 
										the_content();
										// $content = get_the_content();
										// echo apply_filters('block_content' , $content); 
									?>
									<!-- /Dynamic Content -->

									<!-- Pagination -->
									<?php do_action('single_pagination'); ?>
									<!-- /Pagination -->

									<!-- Author box -->
									<?php get_template_part('template-parts/author-box', 'Author box'); ?>
									<!-- /Author box -->

									<!-- Tagss -->
									<div class="tags">
										<?php the_tags('<span class="h2">' . __( 'Tags: ', 'startertheme' ) . '</span>', ', '); // Separated by commas with a line break at the end ?>
									</div>
									<!-- /Tagss -->

									<!-- Related posts block -->
									<?php do_shortcode('[related_posts id="' . get_the_ID() . '"]'); ?> 
									<!-- /Related posts block -->

									<?php edit_post_link(); ?>
									<?php comments_template(); ?>

								</article>
								<!-- /article -->

							<?php endwhile; ?>

							<?php else: ?>

								<!-- article -->
								<article>

									<h1><?php _e('Sorry, nothing to display.', 'startertheme'); ?></h1>

								</article>
								<!-- /article -->

							<?php endif; ?>

						</section>
						<!-- /section -->
					</div>
				</div>
			</div>
		</section>
		<!-- /Main Content Section -->
	</main>

<?php get_footer(); ?>
