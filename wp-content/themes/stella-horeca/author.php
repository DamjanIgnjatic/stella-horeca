<?php get_header(); ?>
	<main role="main">
		<?php
			global $post;
			$author = get_user_by('slug', get_query_var('author_name'));
			if (!is_object($author)) {
				$author = get_user_by('id', $post->post_author);
			}
			$uid = $author->ID;
			$userData = get_userdata($uid);
			$imgId = get_user_meta($uid, 'user_avatar', true);
			$name = $userData->display_name;
			$position = get_user_meta($uid, 'user_position', true);
			$text = get_user_meta($uid, 'user_bio', true);
			$socialMediaLinks = get_field('social_media_links', "user_{$uid}");
		?>
		<section class="section hero-section block bg-light d-flex align-items-center with-logos">
			<div class="container">
				<div class="row pb-4">
					<div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
						<?php if ($uid = get_user_meta($uid, 'user_avatar', true)): ?>
							<?php echo wp_get_attachment_image($imgId, 'profile', false, ['class' => 'member-image']); ?>
						<?php endif; ?>
					</div>
					<div class="col-lg-6 order-last order-lg-first d-flex align-items-center">
						<div class="text-content">
							<h1 class="mb-0"><?php echo $name; ?></h1>
							<div class="small"><?php echo $position; ?></div>
							<div class="text py-3"><?php echo $text; ?></div>
							<?php if ($socialMediaLinks): ?>
								<div class="social-media-links justify-content-sm-start">
									<?php foreach ($socialMediaLinks as $link): ?>
										<?php if (isset($link['link'], $link['link']['url'])): ?>
										<a href="<?php echo $link['link']['url']; ?>" class="link d-inline-flex justify-content-center align-items-center" target="<?php echo $link['link']['target']; ?>" title="<?php echo $link['link']['title']; ?>">
											<?php if (isset($link['icon'])): ?>
												<i class="icon <?php echo $link['icon']; ?>"></i>
											<?php endif; ?>
										</a>
										<?php endif; ?>
									<?php endforeach ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="section section block main-content-section">
			<div class="container py-2">
				<?php if (have_posts()): ?>
					<section class="article-section posts">
						<div class="row g-4">
							<?php while (have_posts()) : the_post(); ?>
								<?php get_template_part('template-parts/blog/post', 'Post'); ?>
							<?php endwhile; ?>
						</div>
						<?php get_template_part('template-parts/pagination', 'Pagination'); ?>
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
 