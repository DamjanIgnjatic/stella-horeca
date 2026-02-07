<?php
/**
 * use BoldizArt\WpTheme\Assets;
 */
namespace BoldizArt\WpTheme;

use BoldizArt\WpTheme\Assets\{
    Styles,
    Scripts,
    Images,
    Fonts,
    DynamicFonts
};

class Assets 
{
    /** @param BoldizArt\WpTheme\Assets\Styles $styles */
    public $styles;

    /** @param BoldizArt\WpTheme\Assets\Scripts $scripts */
    public $scripts;

    /** @param BoldizArt\WpTheme\Assets\Images $images */
    public $images;

    /** @param BoldizArt\WpTheme\Assets\Fonts $fonts */
    public $fonts;

    /** @param BoldizArt\WpTheme\Assets\DynamicFonts $dynamicFonts */
    public $dynamicFonts;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Init
        $this->styles = new Styles();
        $this->scripts = new Scripts();
        $this->images = new Images();
        // $this->fonts = new Fonts();
        $this->dynamicFonts = new DynamicFonts();
    }
}
