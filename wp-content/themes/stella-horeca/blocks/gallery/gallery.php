<?php
/* 
* Block Name: Image
* Post Type: page, post
*/
?>
<?php if (isset($block['data']['previewImage'])): ?>
    <?php do_action('block_image', __FILE__); ?>
<?php else: ?>
    <?php
    // Include helpers
    include get_template_directory() . '/template-parts/base/block-helper.php';

    // Load values and assing defaults
    $title = get_field('title') ?: false;
    $description = get_field('description') ?: false;
    $images = get_field('images') ?: false;
    ?>
    <section class="section section-gallery block <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <div class="section-gallery--title">
                <?php if ($title): ?>
                    <?php echo $title ?>
                <?php endif; ?>
                <?php if ($description): ?>
                    <p><?php echo $description ?></p>
                <?php endif; ?>
            </div>

            <div class="slider animated fade-in">
                <?php
                $count = 0;

                if ($images && is_array($images) && count($images) > 0) :
                    $first_image = $images[0]['image'] ?? '';
                    $last_image = end($images)['image'] ?? '';
                ?>
                    <div class="images-wrapper">
                        <?php if (count($images) > 1): ?>
                            <div class="arrow-prev images-wrapper--arrows">
                                <svg width="42" height="74" viewBox="0 0 42 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <foreignObject x="-21" y="-21" width="84" height="116">
                                        <div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(10.5px);clip-path:url(#bgblur_0_41_141_clip_path);height:100%;width:100%"></div>
                                    </foreignObject>
                                    <path data-figma-bg-blur-radius="21" d="M0 74L9.70393e-06 3.67176e-06L22 6.55671e-06C33.0457 8.00518e-06 42 8.95432 42 20L42 54C42 65.0457 33.0457 74 22 74L0 74Z" fill="black" fill-opacity="0.33" />
                                    <path d="M23 24L12 37.5L23 51" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    <defs>
                                        <clipPath id="bgblur_0_41_141_clip_path" transform="translate(21 21)">
                                            <path d="M0 74L9.70393e-06 3.67176e-06L22 6.55671e-06C33.0457 8.00518e-06 42 8.95432 42 20L42 54C42 65.0457 33.0457 74 22 74L0 74Z" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>

                            <div class="arrow-next images-wrapper--arrows">
                                <svg width="42" height="74" viewBox="0 0 42 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <foreignObject x="-21" y="-21" width="84" height="116">
                                        <div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(10.5px);clip-path:url(#bgblur_0_41_138_clip_path);height:100%;width:100%"></div>
                                    </foreignObject>
                                    <path data-figma-bg-blur-radius="21" d="M42 0L42 74L20 74C8.9543 74 9.7146e-07 65.0457 1.45428e-06 54L2.94047e-06 20C3.42329e-06 8.9543 8.95431 -1.44447e-06 20 -9.6165e-07L42 0Z" fill="black" fill-opacity="0.33" />
                                    <path d="M19 50L30 36.5L19 23" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    <defs>
                                        <clipPath id="bgblur_0_41_138_clip_path" transform="translate(21 21)">
                                            <path d="M42 0L42 74L20 74C8.9543 74 9.7146e-07 65.0457 1.45428e-06 54L2.94047e-06 20C3.42329e-06 8.9543 8.95431 -1.44447e-06 20 -9.6165e-07L42 0Z" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="images-wrapper--large">
                            <?php if (count($images) > 1): ?>
                                <img src="<?php echo esc_url($last_image['url']); ?>"
                                    alt="<?php echo esc_attr($last_image['alt']); ?>"
                                    class="cloned-slide" />
                            <?php endif; ?>

                            <?php foreach ($images as $row) :
                                $image = $row['image'] ?? '';
                            ?>
                                <img data-active="<?php echo $count + 1 ?>" src="<?php echo esc_url($image['url']); ?>"
                                    alt="<?php echo esc_attr($image['alt']); ?>" />

                            <?php $count++;
                            endforeach  ?>

                            <?php if (count($images) > 1): ?>
                                <img src="<?php echo esc_url($first_image['url']); ?>"
                                    alt="<?php echo esc_attr($first_image['alt']); ?>"
                                    class="cloned-slide" />
                            <?php endif; ?>

                        </div>
                        <div class="images-wrapper--open-image">
                            <p>1/20</p>
                            <p>Naziv slike</p>
                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 23V14.7857H3.28571V19.7143H8.21429V23H0ZM0 8.21429V0H8.21429V3.28571H3.28571V8.21429H0ZM14.7857 23V19.7143H19.7143V14.7857H23V23H14.7857ZM19.7143 8.21429V3.28571H14.7857V0H23V8.21429H19.7143Z" fill="white" />
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (count($images) > 1) : ?>
                    <div class="images-wrapper--container">
                        <?php
                        if ($images && is_array($images)) : ?>
                            <div class="images-wrapper--container-small">
                                <?php
                                foreach ($images as $key => $row) :
                                    $image = $row['image'] ?? '';
                                    $data_active = $key + 1;
                                ?>
                                    <img
                                        data-active="<?php echo $data_active ?>"
                                        src="<?php echo esc_url($image['url']); ?>"
                                        alt="<?php echo esc_attr($image['alt']); ?>" />
                                <?php
                                endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="image-modal-modal-wrapper">
            <div class="image-modal">
            </div>
        </div>

        <!-- <div class="empty-section"></div> -->
    </section>
<?php endif; ?>