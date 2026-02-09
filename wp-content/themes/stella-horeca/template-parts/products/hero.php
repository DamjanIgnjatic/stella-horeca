<?php
$products = $args['products'] ?? get_post();
$id  = $products->ID;

// content
$description = get_field("description", $id)
?>
<section class="section section-product-hero">
    <div class="container">
        <div class="section-product-hero--wrapper">
            <h1><?php echo get_the_title($id); ?><span>.</span></h1>
            <p><?php echo $description ?></p>
        </div>
    </div>
</section>