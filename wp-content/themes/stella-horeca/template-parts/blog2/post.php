<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package meatyourfarmer
 */

?>
<div class="col-md-6 mb-5">
	<article id="post-<?php the_ID(); ?>" <?php post_class('h-100 post-article'); ?> >
		<div class="article-wrapper"></div>
		<div class="text-center">
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail('post-thumbnail', [
							'alt' => the_title_attribute([
								'echo' => false
							])
						]
					);
				?>
			</a>
		</div>
		<div class="post-content">
			<?php the_title( '<h2 class="h3 mt-0 text-center">', '</h2>' ); ?>
			<!-- post details -->
			<div class="post-details text-center">
				<div class="detail post-categories pb-2"><?php the_category(' '); ?></div>
				<div class="detail post-date"><i class="fa-regular fa-hourglass"></i> <?php the_time('F j, Y'); ?></div>
				<div class="detail reading-time-block"><?php do_action('reading_time', get_the_content()); ?></div>
				<div class="detail"><?php do_action('page_views'); ?></div>
			</div>
			<!-- /post details -->

			<?php echo wp_trim_words(get_the_content(), 30); ?>
			<div class="read-more">
				<a href="<?php the_permalink(); ?>">Pročitajte više...</a>
			</div>
		</div>
	</article>
</div>
