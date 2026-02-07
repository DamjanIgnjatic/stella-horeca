		<!-- Footer -->
		<footer class="footer light-content" role="contentinfo">
			<?php if (!is_page_template('templates/template-plain.php')): ?>
				<?php if (function_exists('get_field') && function_exists('dynamic_sidebar')): ?>
					<?php $footerSection = get_field('footer_section', 'option') ?: []; ?>
					<?php if ($footerSection && is_array($footerSection)): ?>
						<?php if (array_key_exists('columns', $footerSection) && $columns = $footerSection['columns']): ?>
							<?php if (is_array($columns) && !empty($columns)): ?>
								<section class="footer-body-section">
									<div class="container">
										<div class="row footer-body pt-4">
											<?php foreach ($columns as $no => $column): ?>
												<?php $no++; ?>
												<div class="<?php echo $column['column']; ?> section-<?php echo $no; ?>">
													<?php
														register_sidebar([
															'name' => 'Footer section '.$no,
															'description' => 'Footer section '.$no,
															'id' => "footer-section-{$no}",
															'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
															'after_widget' => '</div>',
															'before_title' => '<h3 class="widget-title">',
															'after_title' => '</h3>',
														]);
													?>
													<?php dynamic_sidebar("footer-section-{$no}"); ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								</section>
							<?php endif; ?>
						<?php endif; ?>
						<?php if (array_key_exists('copyright_text', $footerSection) && $copyright = $footerSection['copyright_text']): ?>
							<div class="<?php echo !is_page_template('templates/template-plain.php') ? 'copyright pt-4' : ''; ?> text-center">
								<?php if ($copyright): ?>
									<small>
										<?php echo str_replace(['[y]', '[Y]'], date('Y'), $copyright); ?>
										<?php if (!str_contains($copyright, 'BoldizArt')): ?>
											<a href="https://boldizart.com/"><?php _e('Web development', 'startertheme'); ?> BoldizArt</a>
										<?php endif; ?>
									</small>
								<?php else: ?>
									<small>&copy; <?php _e('Copyright', 'startertheme'); ?> <?php echo date('Y'); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a> | 
									<?php _e('All rights reserved', 'startertheme'); ?>.</small>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif;?>
		</footer>
		<!-- / Footer end -->

		<div class="bottom-buttons">
			<!-- Scroll to top button -->
			<div class="scroll-to-top bottom-button d-none justify-content-center align-items-center" id="scrollToTop">
				<i class="fa fa-angle-up"></i>
			</div>
		</div>

		<?php wp_footer(); ?>

		<script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
	</body>
</html>
