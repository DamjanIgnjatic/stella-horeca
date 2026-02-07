<?php

/**
 * @author Boldizar Santo <boldizar@stellahoreca.com>
 * @package stellahoreca
 * @link https://stellahoreca.com/
 */
require_once __DIR__ . '/vendor/autoload.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (!defined('ASSETS_VERSION')) {
    define('ASSETS_VERSION', \wp_get_theme()->get('Version'));
}

// ================
// OOP PHP support
// ================

use BoldizArt\WpTheme\{
    AcfSupport,
    Assets,
    Comments,
    CookieConsent,
    CustomPostTypes,
    Defaults,
    Modal,
    Navigation,
    NewsSitemap,
    PageViews,
    Pagination,
    ReadingTime,
    RelatedPosts,
    Search,
    SendFox,
    ThemeOptions,
    UserRestrictions,
    Widgets,
    WoocommerceHelper,
    DynamicModals,
    BlockHelper,
    SocialMediaIcons,
    CustomAdminLogin,
    IframeUrl,
    Captcha,

    // ThemeSwitcher,
};

// Include defaults
$defaults = new Defaults();

// Include Assets 
$assets = new Assets();

// Include Assets 
$themeOptions = new ThemeOptions();

// User restrictions
$userRestrictions = new UserRestrictions();

// Include ACF functions
$acfSupport = new AcfSupport();

// Google news sitemap
$newsSitemap = new NewsSitemap();

// Include Comments 
$acfSupport = new Comments();

// Include Custom post types
$customPostTypes = new CustomPostTypes();

// Include reading time functionality
$readingTime = new ReadingTime();

// Include Modal functionality
$modal = new Modal();

// Incluse the dynamic modals functionaluty
$dynamicModals = new DynamicModals();

// Include related posts
$relatedPosts = new RelatedPosts();

// Include theme switcher functions
// $themeSwitcher = new ThemeSwitcher();

// Include navigarion functions
$navigation = new Navigation();

// Include pagination functions
$pagination = new Pagination();

// Include widgets
$widgets = new Widgets();

// Include SendFox functions
$sendFox = new SendFox();

// Pageviews counter
$pageViews = new PageViews();

// Search functions
$search = new Search();

// Cookie consent function
$search = new CookieConsent();

// Block helper functionality
$blockHelper = new BlockHelper();

// Include social media icons
$smi = new SocialMediaIcons();

// Include custom admin login
$customAdminLogin = new CustomAdminLogin();

// Include iframe URL filter
$iframeUrl = new IframeUrl();

// Include captcha
// $captcha = new Captcha();

// ======================================
// Static functions - Do not remove them
// ======================================

/*
 * Enable WooCommerce scripts if the WooCommerce plugin is installed
 */
if (class_exists('WooCommerce')) {

    // Woocommerce functions
    $woocommerceHelper = new WoocommerceHelper();

    require get_template_directory() . '/woocommerce/wc-functions.php';
}

/**
 * Theme comments callback function
 */
function themeComments($comment, $args, $depth)
{
    do_action('theme_comments', $comment, $args, $depth);
}

/**
 * Return formated projects or false
 * @param name $separator
 * @param string $separator
 * @return mixed
 */
function getProject(string $name = 'projects', string $separator = ' ')
{
    $projects = get_the_terms(get_queried_object_id(), $name);
    $response = false;
    if (is_array($projects) && count($projects)) {
        foreach ($projects as $project) {
            $link = get_term_link($project->slug, 'projects');
            $response .= "<a href='{$link}' rel='project' title='{$project->name}'>{$project->name}</a>";
            $response .= $separator;
        }
    }

    return $response;
}

/**
 * Fetches the content of a template part and returns it as a string.
 *
 * Useful for sending parts of the page as an AJAX response.
 *
 * @param string $template The path to the template part, relative to the theme root.
 * @param string $name The name of the template part (optional).
 * @param array $args The arguments to pass to the template part (optional).
 * @return string The content of the template part.
 */
function fetch_template_part($template, $name = null, $args = [])
{
    ob_start();
    get_template_part($template, $name, $args);
    $response = ob_get_contents();
    ob_end_clean();

    return $response;
}
