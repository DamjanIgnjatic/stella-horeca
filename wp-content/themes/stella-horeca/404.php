<?php get_header(); ?>
<main role="main">
	<?php
	?>
	<?php wp_reset_postdata(); ?>
	<?php if ($page && isset($page->ID)): ?>
		<div class="blocks">
			<?php echo apply_filters('the_content', $page->post_content); ?>
		</div>
	<?php else: ?>
		<div class="section container">
			<div class="error-page">
				<h2>Tražena <strong>stranica</strong> nije pronađena<strong>.</strong></h2>

				<a href="<?php echo home_url(); ?>" class="horeca-btn"><?php _e('Glavna Stranica', 'stellahoreca'); ?></a>
			</div>
		</div>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</main>
<?php get_footer(); ?>