		<!-- Footer -->
		<footer class="section footer light-content" role="contentinfo">
			<?php if (!is_page_template('templates/template-plain.php')): ?>
				<?php if (function_exists('get_field') && function_exists('dynamic_sidebar')): ?>
					<?php $footerSection = get_field('footer_section', 'option') ?: []; ?>
					<?php if ($footerSection && is_array($footerSection)): ?>
						<?php if (array_key_exists('columns', $footerSection) && $columns = $footerSection['columns']): ?>
							<?php if (is_array($columns) && !empty($columns)): ?>
								<div class="container">
									<div class="row footer-body pt-4">
										<?php foreach ($columns as $no => $column): ?>
											<?php $no++; ?>
											<div class="<?php echo $column['column']; ?> section-<?php echo $no; ?>">
												<?php
												register_sidebar([
													'name' => 'Footer section ' . $no,
													'description' => 'Footer section ' . $no,
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
							<?php endif; ?>
						<?php endif; ?>
						<?php if (array_key_exists('copyright_text', $footerSection) && $copyright = $footerSection['copyright_text']): ?>
							<div class="<?php echo !is_page_template('templates/template-plain.php') ? 'copyright' : ''; ?> text-center">
								<?php if ($copyright): ?>
									<small>
										<?php echo str_replace(['[y]', '[Y]'], date('Y'), $copyright); ?>
									</small>
								<?php else: ?>
									<small>&copy; <?php _e('Copyright', 'stellahoreca'); ?> <?php echo date('Y'); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a> |
										<?php _e('All rights reserved', 'stellahoreca'); ?>.</small>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</footer>
		<!-- / Footer end -->

		<div class="bottom-buttons">
			<!-- Scroll to top button -->
			<div class="scroll-to-top bottom-button d-none justify-content-center align-items-center" id="scrollToTop">
				<svg width="68" height="68" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="34" cy="34" r="34" transform="rotate(-90 34 34)" fill="#171717" />
					<path d="M32.5 35C32.5 35.8284 33.1716 36.5 34 36.5C34.8284 36.5 35.5 35.8284 35.5 35L34 35L32.5 35ZM35.0607 11.9393C34.4749 11.3536 33.5251 11.3536 32.9393 11.9393L23.3934 21.4853C22.8076 22.0711 22.8076 23.0208 23.3934 23.6066C23.9792 24.1924 24.9289 24.1924 25.5147 23.6066L34 15.1213L42.4853 23.6066C43.0711 24.1924 44.0208 24.1924 44.6066 23.6066C45.1924 23.0208 45.1924 22.0711 44.6066 21.4853L35.0607 11.9393ZM34 35L35.5 35L35.5 13L34 13L32.5 13L32.5 35L34 35Z" fill="#A800BA" />
				</svg>

			</div>
		</div>

		<?php wp_footer(); ?>

		<script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
		</body>

		</html>