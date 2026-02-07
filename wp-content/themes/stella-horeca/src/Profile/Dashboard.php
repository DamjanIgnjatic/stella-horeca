<?php
/**
 * use BoldizArt\WpTheme\Profile\Dashboard;
 */
namespace BoldizArt\WpTheme\Profile;

class Dashboard extends Profile
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Call the parents construct
        parent::__construct();

        // Add actions
        if (function_exists('add_action')) {
            // Init function
            \add_action('init', [$this, 'handler']);
        }
    
        // Add shortcodes
        if (function_exists('add_shortcode')) {
            add_shortcode('profile_dashboard', [$this, 'display']);
        }
    }

    /**
     * Create a user profile
     * @shortcode [profile_dashboard]
     */
    public function display()
    {
        $response = '';
        if ($this->user->ID) {
            $payload = [
                'user' => $this->user
            ];
            \get_template_part('template-parts/profile/dashboard', 'Dashboard', $payload);
        }

        return $response;
    }

    /**
     * Registration validation
     */
    public function handler()
    {

    }
}
