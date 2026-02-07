<?php $no = 1; if (have_posts()): while (have_posts()) : the_post(); ?>
	
	<article id="post-<?php the_ID(); ?>" <?php post_class('post no-'.$no); $no++; ?>>
		<div class="row">

			<!-- article -->
			<div class="col-sm-4">

				<!-- post thumbnail -->
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php if (has_post_thumbnail()) : // Check if thumbnail exists ?>
						<?php the_post_thumbnail([460, 360]); ?>
					<?php endif; ?>
				</a>
				<!-- /post thumbnail -->

				<?php edit_post_link(); ?>
			</div>
			<div class="col-sm-8">

				<!-- post details -->
				<div class="post-body">
					<h5 class="post-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_title(); ?>
						</a>
					</h5>
					<p class="text">
						<small class="post-date"><i class="fa-regular fa-hourglass"></i> <?php the_time('F j, Y'); ?></small>
						<?php if (has_category()): ?>
						<small class="post-categories">
							<i class="fa fa-folder-o icon"></i> 
							<?php the_category(', '); ?>
						</small>
						<?php endif; ?>
					</p>
					<?php do_action('theme_excerpt', get_the_content(), 14); ?>
				</div>
				<!-- /post details -->
			</div>

		</div>
	</article>
	<!-- /article -->

	<?php endwhile; ?>
<?php endif; ?>
