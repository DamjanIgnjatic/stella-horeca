<?php
/**
 * use BoldizArt\WpTheme\Profile\Profile;
 */
namespace BoldizArt\WpTheme\Profile;

class Profile
{
    /** @param WP_User $user */
    public $user;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Get the current user
        $this->user = \wp_get_current_user();

        // Add actions
        if (function_exists('add_action')) {
            
            // Redirect if not logged in
            \add_action('template_redirect', [$this, 'redirect']);
        }
    }

    /**
     * Redirect if not logged in
     */
    public function redirect()
    {
        if (!$this->user->ID) {
            $type = \get_post_type();
            if (is_single() && $type == 'profile') {
                \wp_safe_redirect(\home_url());
                exit;
            }
        }
    }
}
