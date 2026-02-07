<?php
/**
 * use BoldizArt\WpTheme\Assets\Images;
 */
namespace BoldizArt\WpTheme\Assets;

class Images
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('wp_kses_allowed_html', [$this, 'svgSupport'], 10, 2);

            // Theme support
            \add_action('init', [$this, 'themeSupport']);

            // Image sizes
            \add_filter('intermediate_image_sizes_advanced', [$this, 'removeUnusedImageSizes']);
            \add_action('init', [$this, 'customImageSizes']);

            // Login logo
            add_action('login_enqueue_scripts', [$this, 'loginLogo']);
        }
    }

    /**
     * SVG support
     * @param mixed $tags
     */
    function svgSupport($tags)
    {
        $tags['svg'] = [
            'xmlns' => [],
            'fill' => [],
            'viewbox' => [],
            'role' => [],
            'aria-hidden' => [],
            'focusable' => [],
        ];
        $tags['path'] = [
            'd' => [],
            'fill' => [],
        ];

        return $tags;
    }

    /**
     * Add theme support
     */
    public function themeSupport()
    {
        if (function_exists('add_theme_support')) {
            // Add support for custom logo
            \add_theme_support('custom-logo', [
                'width' => 280,
                'height' => 90,
                'flex-width' => true,
                'flex-height' => true,
            ]);
        
            // Add thumbnail support
            \add_theme_support('post-thumbnails');
        }
    }

    /**
     * Add custom image sizes
     */
    public function customImageSizes()
    {
        if (function_exists('add_image_size')) {
            // Set custom image sizes
            \add_image_size('profile', 480, 480, true);
            \add_image_size('service', 720, 480, true);
        }
    }

    /**
     * Remove unused image sizes
     * @param array $sizes
     * 
     * @return array $sizes
     */
    function removeUnusedImageSizes($sizes) {
        unset($sizes['medium']);
        unset($sizes['large']);
        unset($sizes['medium_large']);

        return $sizes;
    }

    /**
     * Add custom login logo
     */
    function loginLogo ()
    { 
        if (has_custom_logo()) {
            $logoUrl = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
            ?>
            <style type="text/css">
                #login h1 a, .login h1 a {
                    background-image: url(<?php echo $logoUrl; ?>);
                    width: 100% !important;
                    background-size: contain;
                    max-height: 150px;
                    padding-bottom: 30px;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var login = document.getElementById('login');
                    if (login) {
                        var link = login.querySelector('h1 > a');
                        if (link) {
                            link.setAttribute('href', '/');
                            link.textContent = '<?php echo get_bloginfo('name'); ?>';
                        }
                    }
                });
            </script>
            <?php
        }
    }
}
