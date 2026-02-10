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

<body <?php body_class('stellahoreca'); ?>>
	<?php if (!is_page_template('templates/template-plain.php')): ?>
		<header class="section header">
			<div class="container">
				<div class="header--wrapper">
					<div class="left-nav">
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
					<div class="middle-nav">
						<?php do_action('theme_navigation'); ?>
					</div>
					<div class="right-nav">
						<a href="horeca-btn">Kontakirajte nas</a>
					</div>
				</div>
			</div>
		</header>
	<?php endif; ?>