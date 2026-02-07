<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<?php if ($iconUrl = get_site_icon_url()): ?>
			<link href="<?php echo $iconUrl; ?>" rel="shortcut icon prefetch dns-prefetch">
			<link href="<?php echo $iconUrl; ?>" rel="apple-touch-icon-precomposed prefetch dns-prefetch">
		<?php endif; ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">

		<?php wp_head(); ?>
	</head>
	<body <?php body_class('startertheme'); ?>>
		<?php if (!is_page_template('templates/template-plain.php')): ?>
			<section class="section navigation-section" id="navbar">
				<div class="container">
					<div class="navigation" id="mainNavigation">
						<div class="left-nav">
							<div class="nav-logo">
								<a href="<?php echo home_url(); ?>" class="home-link" aria-label="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
								<?php if (has_custom_logo()): ?>
										<?php $logoUrl = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'); ?>
										<link rel="preload" href="<?php echo esc_url($logoUrl); ?>" as="image">
										<?php echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'site-logo skip-lazy no-lazy']); ?>
									<?php else: ?>
										<?php echo bloginfo('name'); ?>
									<?php endif; ?>
								</a>
							</div>
						</div>
						<div class="middle-nav">
							<div class="theme-menu-wrapper d-lg-flex align-items-center justify-content-end">
								<?php do_action('theme_navigation'); ?>
								<?php $navBtns = get_field('navigation_buttons', 'option'); ?>
								<?php if ($navBtns && is_array($navBtns)): ?>
									<div class="nav-buttons">
										<?php foreach ($navBtns as $val): ?>
											<?php if (is_array($val) && isset($val['link']) && is_array($val['link'])): $link = $val['link']; ?>
												<?php $iconId = isset($val['icon']) && $val['icon'] ? $val['icon'] : false; ?>
												<?php $type = isset($val['type']) && $val['type'] ? $val['type'] : 'btn-first'; ?>
												<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" 
													class="button btn ms-3 <?php echo $type; ?> my-2 me-2 <?php echo $iconId ? 'btn-icon' : ''; ?>"
													style="min-width: 120px;"
												>
													<?php echo $link['title']; ?>
													<?php if ($iconId): ?>
														<?php echo wp_get_attachment_image($iconId, 'full', false, ['class' => 'icon']); ?>
													<?php endif; ?>
												</a>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="right-nav d-flex align-items-center justify-content-end">
							<div class="language-switcher"></div>
							<div class="theme-switcher">
								<?php do_action('theme_switcher'); ?>
							</div>
							<div class="menu-toggle d-inline-flex right d-lg-none" id="menuToggle">
								<div class="bar"></div>
								<div class="bar"></div>
								<div class="bar"></div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
