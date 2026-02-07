<?php get_header(); ?>

  <div id="content" class="site-content container py-5 mt-4">
    <div id="primary" class="content-area">
      <main id="main" class="site-main">
        <?php woocommerce_breadcrumb(); ?>
        <?php woocommerce_content(); ?>
        <?php get_sidebar(); ?>
      </main><!-- #main -->
    </div><!-- #primary -->
  </div><!-- #content -->

<?php get_footer();
