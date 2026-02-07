<?php
/**
 * use BoldizArt\WpTheme\Assets\DynamicFonts;
 */
namespace BoldizArt\WpTheme\Assets;

class DynamicFonts
{
    /** @param array $fonts */
    public $fonts = [];

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('init', [$this, 'init']);

            // Add fonts to head
            \add_action('wp_head', [$this, 'addFonts']);

            // Add styleseets
            \add_action('wp_enqueue_scripts', [$this, 'addStyles']);
        }
    }

    /**
     * On init function get the option data and set into the variables
     */
    public function init()
    {
        // Setup option data
        if (function_exists('get_field')) {
            $this->fonts['primary'] = get_field('primary_font', 'option') ?: false;
            $this->fonts['secondary'] = get_field('secondary_font', 'option') ?: false;
        }
    }

    /**
     * Add Google font
     */
    public function addFonts()
    {
        // Set the response
        $response = '';

        // Set fonts
        $googlePreconnect = false;
        $fontLinks = [];
        if ($this->fonts && is_array($this->fonts) && !empty($this->fonts)) {
            foreach ($this->fonts as $type => $font) {
                if (is_array($font)) {
                    if (array_key_exists('use_local_fonts', $font) && $font['use_local_fonts']) {
                        // Preload local fonts
                        if (array_key_exists('local_fonts', $font) && $fonts = $font['local_fonts']) {
                            foreach ($fonts as $data) {
                                if (array_key_exists('font_file', $data) && is_array($data['font_file'])) {
                                    $response .= '<link rel="preload" href="' . $data['font_file']['url'] . '" as="font" type="' . $data['font_file']['mime_type'] . '" crossorigin>';
                                }
                            }
                        }
                    } else {
                        // Preconnect
                        if (!$googlePreconnect) {
                            $response .= '
                                <link rel="preconnect" href="https://fonts.googleapis.com">
                                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                            ';
                            $googlePreconnect = true;
                        }

                        // Preload google font
                        $fontName = str_replace(' ', '+', $font['google_font']);
                        if ($type == 'primary') {
                            $response .= '<link rel="preload" href="https://fonts.googleapis.com/css2?family=' . $fontName . ':wght@' . $font['font_weight_light'] . ';' . $font['font_weight_normal'] . ';' . $font['font_weight_bold'] . '" as="font" crossorigin>';
                        }

                        // Load Google fonts
                        $fontLink = '<link href="https://fonts.googleapis.com/css2?family=' . $fontName . ':wght@' . $font['font_weight_light'] . ';' . $font['font_weight_normal'] . ';' . $font['font_weight_bold'] . '&display=swap" rel="stylesheet">';
                        if (!in_array($fontLink, $fontLinks)) {
                            $response .= $fontLink;
                            $fontLinks[] = $fontLink;
                        }
                    }
                }
            }
        }

        echo $response;
    }

    public function addStyles()
    {
        // Set the css
        $css = '';

        // Set fonts
        if ($this->fonts && is_array($this->fonts) && !empty($this->fonts)) {
            foreach ($this->fonts as $type => $font) {
                if (is_array($font)) {
                    if (array_key_exists('use_local_fonts', $font) && $font['use_local_fonts']) {
                        // Preload local fonts
                        if (array_key_exists('local_fonts', $font) && $fonts = $font['local_fonts']) {
                            foreach ($fonts as $data) {
                                if (array_key_exists('font_file', $data) && is_array($data['font_file'])) {
                                    $css .= "                            
                                        @font-face {
                                            font-family: " . $font['font_family'] . ";
                                            src: url('" . $data['font_file']['url'] . "') format('" . $data['font_file']['subtype'] . "');
                                            font-weight: " . $data['font_weight'] . ";
                                            font-style: " . $data['font_style'] . ";
                                            font-display: swap;
                                        }
                                    ";
                                }
                            }
                        }
                               
                        print_r($font);

                        // Include font-families
                        $css .= "
                            :root {
                                --{$type}-font: {$font['font_family']};
                            }
                        ";
                    } else {
                        // Include font-families
                        if (array_key_exists('google_font', $font)) {
                            $css .= "
                                :root {
                                    --{$type}-font: '{$font['google_font']}', sans-serif;
                                }
                            ";
                        }
                    }

                    // Set font weight
                    $css .= "
                        :root {
                            --{$type}-font-weight-light: {$font['font_weight_light']};
                            --{$type}-font-weight-normal: {$font['font_weight_normal']};
                            --{$type}-font-weight-bold: {$font['font_weight_bold']};
                        }
                    ";
                }
            }
        }

        // Add the CSS code to the main style
        wp_add_inline_style('startertheme-theme-style', $css);
    }
}
