<?php
/**
 * use BoldizArt\WpTheme\WoocommerceHelper;
 */
namespace BoldizArt\WpTheme;

use BoldizArt\WpTheme\URL;

class WoocommerceHelper
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Enable WooCommerce functions only if plugin is installed
        if (class_exists('WooCommerce')) {
            // Add actions
            if (function_exists('add_action')) {

                // Add profile pages
                add_action('init', [$this, 'addProfileMenuEndpoints']);
                add_action('woocommerce_account_dashboard', [$this, 'dashboardPageContent'], 10, 1);

                // Cart redirect
                \add_action('template_redirect', [$this, 'redirects']);
            }

            // Add filter
            if (function_exists('add_filter')) {
                // add_filter('woocommerce_show_page_title', [$this, 'hideWoocommercePageTitles']);

                // Add profile pages filter
                add_filter('woocommerce_account_menu_items', [$this, 'addProfileMenuItems']);

                // Form input filters
                add_filter('woocommerce_checkout_fields', [$this, 'addBootstrapClassesToCheckoutFields']);
                add_filter('woocommerce_form_field_args', [$this, 'addFieldsArguments'], 10, 3);

                // Chenge the currency symbol
                add_filter('woocommerce_currency_symbol', [$this, 'changeSymbol'], 10, 2);
            }
        }
    }

    /**
     * Redirect users from product, categories and cart pages
     */
    function redirects()
    {
        // Redirect anonymous users from my account to login page
        if (function_exists('is_account_page') && is_account_page() && !is_user_logged_in()) {
            \wp_redirect(URL::create('login'));
            exit;
        }

        // Redirect anonymous user from the checkout to the login page
        if (function_exists('is_checkout') && is_checkout() && !is_user_logged_in()) {
            \wp_redirect(URL::create('login'));
            exit;
        }

        // Redirect anonymous from the WooCommerce lost password page to a custom lost password page
        if (function_exists('is_lost_password_page') && is_lost_password_page() && !is_lost_password_page()) {
            \wp_redirect(URL::create('lost-password'));
            exit;
        }
    }

    /**
     * Change the profile account links
     * @param array $menu_links
     */
    public function addProfileMenuItems($menu_links)
    {
        // Disable Downloads
        unset($menu_links['downloads']);
        unset($menu_links['payment-methods']);
        unset($menu_links['subscriptions']);

        // Rename menu items
        $menu_links['dashboard'] = __('Overview', 'woocommerce');

        // // Add a new menu links
        // // Reservations
        // $menu_links = array_slice($menu_links, 0, 1, true) 
        //     + ['reservations' => __('Reservations', 'startertheme')]
        //     + array_slice($menu_links, 1, NULL, true);
        return $menu_links;
    }

    /**
     * Add profile page endpoints
     */
    public function addProfileMenuEndpoints()
    {
        // WP_Rewrite
        // add_rewrite_endpoint('portfolio', EP_PAGES);
    }

    /**
     * Change the dashboard content (Overview)
     */
    public function dashboardPageContent()
    {
        // Get the current user
        $user = \wp_get_current_user();

        // Get order details
        get_template_part('template-parts/profile/dashboard', 'Dashboard', [
            'user' => $user,
        ]);
    }

    /** 
     * Hide the page title on specific pages
     * @param string $title
     * @return string|bool $title
    */
    function hideWoocommercePageTitles($title) {
        if (\is_shop() || \is_product_category()) {
            $title = false;
        }

        return $title;
    }

    /**
     * Check if the current page a woocommerce one or not
     * @return bool
     */
    public static function isWooPage() : bool
    {
        // Check if WooCommerce is active and the required class exists
        if (class_exists('WooCommerce')) {
            if (function_exists('is_woocommerce')) {
                return is_woocommerce() || is_cart() || is_checkout() || is_shop() || is_product() || is_product_category();
            }
        }

        return false;
    }

    /**
     * Get products by criteria
     * @param string $criteria
     * @param int limit
     * @param array $excluded
     */
    public static function getProductsByCriteria($criteria, $limit = 8, $excluded = [])
    {
        global $wpdb;
        $excludedIds = implode(',', $excluded);

        $order_by = '';
        switch ($criteria) {
            case 'popular':
                $order_by = 'ORDER BY p.post_views DESC';
                break;
            case 'price_high':
                $order_by = 'ORDER BY meta.meta_value+0 DESC';
                break;
            case 'price_low':
                $order_by = 'ORDER BY meta.meta_value+0 ASC';
                break;
            case 'newest':
            default:
                $order_by = 'ORDER BY p.post_date DESC';
                break;
        }
    
        $query = $wpdb->prepare(
            "SELECT p.ID
            FROM {$wpdb->prefix}posts p
            LEFT JOIN {$wpdb->prefix}postmeta meta ON p.ID = meta.post_id AND meta.meta_key = '_price'
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND p.ID NOT IN ({$excludedIds})
            {$order_by}
            LIMIT %d",
            $limit
        );
    
        $product_ids = $wpdb->get_col($query);
        $product_ids = array_merge($excluded, $product_ids);

        $products = [];
        foreach ($product_ids as $product_id) {
            $products[] = wc_get_product($product_id);
        }
    
        return $products;
    }

    /**
     * Add Bootstrap classes to the checkout form fields
     * @param array $fields
     * @return array $fields
     */
    function addBootstrapClassesToCheckoutFields($fields) {
        foreach ($fields as &$fieldset) {
            foreach ($fieldset as &$field) {
                $field['class'][] = 'form-group'; 
                $field['input_class'][] = 'form-control';
            }
        }

        return $fields;
    }
    
    /**
     * Aff arguments to the WooCommerce form fields
     * @param array $args
     * @return array $args
     */
    function addFieldsArguments($args) : array
    {
        // Start field type switch case
        switch ($args['type']) {
    
            case 'select':
                $args['class'][] = 'form-group';
                $args['input_class'] = ['form-control', 'form-select'];
                $args['label_class'] = ['control-label'];
                $args['custom_attributes'] = ['data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true'];
            break;
    
            case 'country':
                $args['class'][] = 'form-group single-country';
                $args['input_class'] = ['form-control', 'form-select'];
                $args['label_class'] = ['control-label'];
            break;
    
            case "state":
                $args['class'][] = 'form-group';
                $args['input_class'] = ['form-control', 'input-lg'];
                $args['label_class'] = ['control-label'];
                $args['custom_attributes'] = ['data-plugin' => 'select2', 'data-allow-clear' => 'true', 'aria-hidden' => 'true'];
            break;
    
            case "password" :
            case "text" :
            case "email" :
            case "tel" :
            case "number" :
                $args['class'][] = 'form-group';
                $args['input_class'] = ['form-control', 'input-lg'];
                $args['label_class'] = ['control-label'];
            break;
    
            case 'textarea' :
                $args['input_class'] = ['form-control', 'input-lg'];
                $args['label_class'] = ['control-label'];
            break;
    
            case 'checkbox' :  
            break;
    
            case 'radio' :
            break;
    
            default :
                $args['class'][] = 'form-group';
                $args['input_class'] = ['form-control', 'input-lg'];
                $args['label_class'] = ['control-label'];
            break;
        }
    
        return $args;
    }

    /**
     * Change the currency symbol text
     */
    function changeSymbol($symbol, $currency)
    {
        switch(strtolower($currency)) {
            case 'rsd': 
                $symbol = 'RSD';
                break;
        }

        return $symbol;
    }
}
