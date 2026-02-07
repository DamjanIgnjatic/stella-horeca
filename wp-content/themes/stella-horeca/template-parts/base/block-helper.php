<?php
// Check if disabled
$disable = get_field('disable') ?: false;

if (!$disable) {
    // Get the section classes
    $classNames = [
        isset($block['className']) ? $block['className'] : false,
        isset($block['align']) ? $block['align'] : false,
        get_field('background_color') ?: false,
        get_field('section_class') ?: false,
        get_field('show_grid') ? 'show-grid' : false,
        get_field('combinated_with_logos') ? 'with-logos' : false,
        get_field('padding_top') ?: false,
        get_field('padding_bottom') ?: false,
        get_field('full_height_mobile') ? 'full-height-mobile' : false,
        get_field('full_height_tablet') ? 'full-height-tablet' : false,
        get_field('full_height_desktop') ? 'full-height-desktop' : false
    ];

    // Get the section id
    $sectionId = get_field('section_id') ?: false;

    // Get other variables
    $title = get_field('title') ?: false;
    $text = get_field('text') ?: false;
    $cta = get_field('cta') ?: '';
    $bgUrl = get_field('background_image') ?: false;
    $mobileBgUrl = get_field('mobile_background_image') ?: false;
    $contentWidth = get_field('content_width') ?: false;

    // Responsive bg
    if ($bgUrl && $mobileBgUrl) {
        $classNames[] = 'responsive-bg';
    }

    // Explode CSS classes
    $classNames = array_filter($classNames, function ($x) {
        return strlen($x) > 2;
    });
    $className = implode(' ', $classNames);
}
