<?php get_header(); ?>
	<main role="main">
		<?php 
			$pid = get_field('custom_404_page', 'option'); 
			if (function_exists('pll_get_post')) {
				$pid = pll_get_post($pid);
			}
			$page = get_page($pid);
		?>
		<?php wp_reset_postdata(); ?>
		<?php if ($page && isset($page->ID)): ?>
			<div class="blocks">
				<?php echo apply_filters('the_content', $page->post_content); ?>
			</div>
		<?php else: ?>
			<div class="content text-center">
				<h1 class="h1 h0">404</h1>
				<p class="text">
					<b><?php _e('Ouch!', 'startertheme'); ?></b><br>
					<?php _e('It looks that the page will not arrive.', 'startertheme'); ?><br>
					<?php _e('Don\'t wait more!', 'startertheme'); ?>
				</p>
				<a href="<?php echo home_url(); ?>" class="btn go-home"><?php _e('Go Home', 'startertheme'); ?></a>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</main>
<?php get_footer(); ?>
