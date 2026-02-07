<?php
/**
 * use BoldizArt\WpTheme\Assets\Fonts;
 */
namespace BoldizArt\WpTheme\Assets;

class Fonts
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Add fonts to head
            \add_action('wp_head', [$this, 'addFonts']);
        }
    }

    /**
     * Add Google font
     */
    public function addFonts()
    {
        // Add Google fonts
        if (function_exists('get_field') && $gfu = get_field('google_fonts_url', 'option')) {
            echo '
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="' . $gfu . '" rel="stylesheet">
            ';
        } else {
            $this->addLocalFonts();
        }
    }

    /**
     * WprdPress init function
     */
    public Function addLocalFonts()
    {
        ?>
        <!-- Preload fonts -->
        <link rel="preload" href="<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-SemiBold.woff2" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Light.woff2" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Regular.woff2" as="font" type="font/woff2" crossorigin> 
        
        <!-- Add font faces -->
        <style>
            @font-face {
                font-family: 'Poppins';
                src: url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-SemiBold.woff2') format('woff2'),
                    url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-SemiBold.woff') format('woff');
                font-weight: bold;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: 'Poppins';
                src: url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Light.woff2') format('woff2'),
                    url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Light.woff') format('woff');
                font-weight: light;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: 'Poppins';
                src: url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Regular.woff2') format('woff2'),
                    url('<?php echo get_template_directory_uri() ?>/dist/fonts/poppins/Poppins-Regular.woff') format('woff');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }
        </style>
        <?php 
    }
}
