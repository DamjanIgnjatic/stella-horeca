<?php
/**
 * use BoldizArt\WpTheme\Navigation;
 */
namespace BoldizArt\WpTheme;

class Navigation
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add filter
        if (function_exists('add_filter')) {

            // Navigation
            add_filter('wp_nav_menu_args', [$this, 'menuArgs']);
            add_filter('show_admin_bar', [$this, 'removeAdminBar']);

            // Add menu item classes
            add_filter('wp_update_nav_menu_item', [$this, 'addMenuItemClasses'], 10, 2);
            // add_filter('nav_menu_css_class', [$this, 'displayMenuItemClasses'], 1, 2);
        }

        // Add actions
        if (function_exists('add_action')) {

            // Add navigation action
            add_action('theme_navigation', [$this, 'themeNavigation'], 10);

            // Add menu item classes
            add_action('wp_nav_menu_item_custom_fields', [$this, 'addCheckboxesToEachMenuItem'], 1);
        }
    }

    /**
     * Remove the <div> surrounding the dynamic navigation to cleanup markup
     */
    public function menuArgs($args = '')
    {
        $args['container'] = false;
        return $args;
    }

    /**
     * Remove Admin bar from non-admin users
     */
    public function removeAdminBar()
    {
        return current_user_can('administrator') ? true : false;
    }

    /**
     * StarterTheme navigation
     */
    public function themeNavigation()
    {
        wp_nav_menu([
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<div class="theme-menu-content"><ul>%3$s</ul></div>',
            'depth'           => 0,
            'walker'          => ''
        ]);
    }

    /**
     * Filter to modify menu item on save
     * @param int $menuId
     * @param int $itemId
     * 
     * @return int $menuId
     */
    function addMenuItemClasses($menuId, $itemId)
    {
        // Set classes
        if ($itemId && (isset($_POST['menu-item-disable-on-mobile']) || isset($_POST['menu-item-disable-on-desktop']))) {
            $mobile = isset($_POST['menu-item-disable-on-mobile'], $_POST['menu-item-disable-on-mobile'][$itemId]) ? true : false;
            $desktop = isset($_POST['menu-item-disable-on-desktop'], $_POST['menu-item-disable-on-desktop'][$itemId]) ? true : false;
    
            // Update the item meta
            update_post_meta($itemId, 'menu-item-disable-on-mobile', $mobile);
            update_post_meta($itemId, 'menu-item-disable-on-desktop', $desktop);
        }
    
        return $menuId;
    }
    
    /**
     * Additional navigation classes
     * @param array $classes
     * @param WP_Post $item
     */
    function displayMenuItemClasses($classes, $item)
    {
        // Check meta data by item id
        $mobile = get_post_meta($item->ID, 'menu-item-disable-on-mobile', true);
        $classes[] = $mobile ? 'd-none' : 'd-block';
    
        // Add desktop classes
        $desktop = get_post_meta($item->ID, 'menu-item-disable-on-desktop', true);
        $classes[] = $desktop ? 'd-lg-none' : 'd-lg-block';
    
        return $classes;
    }
    
    /**
     * Add checkboxes to each menu item
     * @param WP_Object $item
     */
    function addCheckboxesToEachMenuItem($itemId)
    {
        $mobile = get_post_meta($itemId, 'menu-item-disable-on-mobile', true);
        $desktop = get_post_meta($itemId, 'menu-item-disable-on-desktop', true);
        ?>
        <hr />
        <p class="description">
            <label for="menu-item-disable-on-mobile-<?php echo $itemId; ?>">
                <input type="checkbox" id="menu-item-disable-on-mobile-<?php echo $itemId; ?>" name="menu-item-disable-on-mobile[<?php echo $itemId; ?>]" value="1" <?php echo $mobile ? 'checked' : ''; ?> />
                <?php _e('Disable on mobile', 'doctor-theme'); ?>
            </label>
        </p>
        <p class="description">
            <label for="menu-item-disable-on-desktop-<?php echo $itemId; ?>">
                <input type="checkbox" id="menu-item-disable-on-desktop-<?php echo $itemId; ?>" name="menu-item-disable-on-desktop[<?php echo $itemId; ?>]" value="1" <?php echo $desktop ? 'checked' : ''; ?> />
                <?php _e('Disable on desktop', 'doctor-theme'); ?>
            </label>
        </p>
        <hr />
        <?php 
    }
}
