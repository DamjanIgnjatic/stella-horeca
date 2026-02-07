<!-- sidebar -->
<aside class="sidebar h-100" role="complementary">

	<?php get_template_part('searchform'); ?>

	<div class="sidebar-widget">
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
	</div>

	<?php // get_template_part('subscribe'); ?>

</aside>
<!-- /sidebar -->
