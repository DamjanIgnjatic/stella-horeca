<?php
/**
 * use BoldizArt\WpTheme\UserRestrictions;
 */
namespace BoldizArt\WpTheme;

class UserRestrictions
{
    /** @var array $allowedIds An array of allowed user IDs. */
    private $allowedIds;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Set allowed users
        $this->allowedIds = [1, 1298];

        // Add actions
        if (function_exists('add_action')) {

            // Admin menu items controll
            add_action('admin_menu', [$this, 'controlAdminMenuItems']);
        }

        // Add filter
        if (function_exists('add_filter')) {

            // Restrict admin modifications
            add_filter('user_has_cap', [$this, 'restrictAdminModifications'], 10, 3);
        }
    }

    /**
     * Restricts admin capabilities for critical operations.
     *
     * This method modifies the capabilities of users trying to perform critical
     * operations such as updating themes, plugins, or the core, installing themes,
     * deleting plugins or themes, and promoting users. Only users with IDs in the
     * allowedIds list are permitted to execute these operations.
     *
     * @param array $allcaps An array of all the capabilities of the user.
     * @param array $cap     The capability being checked.
     * @param array $args    Additional arguments.
     * 
     * @return array Modified capabilities array.
     */

    public function restrictAdminModifications($allcaps, $cap, $args)
    {
        // Set critical capabilities
        $critical = [
            'update_themes', 
            'update_plugins',
            'delete_plugins',
            'install_themes',
            'update_core',
            'delete_themes',
            'promote_users'
        ];

        if (isset($args[0]) && in_array($args[0], $critical)) {
            if (!is_user_logged_in() || !in_array(get_current_user_id(), $this->allowedIds)) {
                $allcaps[$args[0]] = 0;
            }
        }

        return $allcaps;
    }

    /**
     * Hides the 'Update Core' submenu item from the WordPress admin dashboard.
     *
     * This function removes the submenu page 'update-core.php' from the 'Dashboard'
     * menu for all users. It is hooked into the 'admin_menu' action.
     */
    public function controlAdminMenuItems()
    {
        if (!is_user_logged_in() || !in_array(get_current_user_id(), $this->allowedIds)) {
            remove_submenu_page('index.php', 'update-core.php');
        }
    }
}
