<?php
/**
 * use BoldizArt\WpTheme\ThemeSwitcher;
 */
namespace BoldizArt\WpTheme;

class ThemeSwitcher
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('theme_switcher', [$this, 'themeSwitcher']);
            \add_action('wp_head', [$this, 'addMetaTags']);
        }
    }


	/**
	 * Theme switcher
	 */
	function themeSwitcher()
	{
		echo '<span id="switcher-button-container"></span>';
	}

	/**
	 * Add metatags to head
	 */
	public function addMetaTags()
	{
		$themeColor = isset($_COOKIE['theme_style']) && $_COOKIE['theme_style'] == 'dark' ? '#222222' : '#f9f9f9';
		?>
			<!-- Chrome, Firefox OS and Opera -->
			<meta name="theme-color" id="meta-color" content="<?php echo $themeColor; ?>">
			<!-- Windows Phone -->
			<meta name="msapplication-navbutton-color" id="windows-meta-color" content="<?php echo $themeColor; ?>">
			<!-- iOS Safari -->
			<meta name="apple-mobile-web-app-status-bar-style" id="safari-meta-color" content="<?php echo $themeColor; ?>">
		<?php
	}
}
