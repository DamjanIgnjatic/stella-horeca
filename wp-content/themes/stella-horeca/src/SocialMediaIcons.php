<?php
/**
 * use BoldizArt\WpTheme\SocialMediaIcons;
 */
namespace BoldizArt\WpTheme;

class SocialMediaIcons
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add shortcodes
        if (function_exists('add_shortcode')) {

            // Register shortcode
            \add_shortcode('social_media_icons', [$this, 'socialMediaIcons']);
        }
    }

    /**
     * Register a shortcode for social media icons
     * @shortcoode [social_media_icons]
     */
    function socialMediaIcons()
    {
        $response = '';
        $socialMedia = get_field('social_media', 'option');
        if ($socialMedia && is_array($socialMedia)) {
            $response .= '
                <div class="social-media-icons">
                    <p class="pt-3 mb-2"><strong>' . get_field('social_media_label', 'option') . '</strong></p>
                    <div class="social-media-links d-flex align-items-center">
            ';
                foreach ($socialMedia as $value) {
                    if (isset($value['icon_class'], $value['link']) && $link = $value['link']) {
                        if (isset($link['url'], $link['target'])) {
                            $response .= '
                                <a href="' . $link['url'] . '" target="' . $link['target'] . '" class="link d-inline-flex justify-content-center align-items-center">
                                    <i class="icon ' . $value['icon_class'] . '"></i>
                                </a>
                            ';
                        }
                    }
                }
            $response .= '
                    </div>
                </div>
            ';
        }

        return $response;
    }
}
