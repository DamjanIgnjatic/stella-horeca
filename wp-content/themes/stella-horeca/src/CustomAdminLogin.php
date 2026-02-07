<?php
/**
 * use BoldizArt\WpTheme\CustomAdminLogin;
 */
namespace BoldizArt\WpTheme;

class CustomAdminLogin
{
    /** @param array $fields */
    public $fields = [];

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Set fields
        $this->fields = [
            'login' => [
                'title' => __('Custom admin login URL', 'startertheme'),
                'description' => __('Set a custom admin login URL to protect your login page', 'startertheme'),
                'slug' => 'aeva'
            ],
            'redirect' => [
                'title' => __('Custom admin redirect URL', 'startertheme'),
                'description' => __('Set a custom admin 404 redirect URL if someone tryes to access wp-admin', 'startertheme'),
                'slug' => '404'
            ]
        ];

        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('init', [$this, 'redirectToActualLogin']);
            \add_action('admin_init', [$this, 'adminLoginUrlSettings']);
            \add_action('admin_init', [$this, 'addCustomFields']);
            \add_action('login_head', [$this, 'adminFailedRedirect']);
            \add_action('admin_init', [$this, 'updateData']);
        }
    }

    /**
     * Add field to permalinks page
    */
    function adminLoginUrlSettings()
    {
        \add_settings_section(
            'custom_admin_login_section',
            'Custom admin login',
            [$this, 'customAdminAuthSection'],
            'permalink'
        );
    
        \register_setting('permalink', 'custom_admin_auth_login');
    }
    
    /**
     * Custom admin login section
     */
    public function customAdminAuthSection()
    {
        echo '<p>' . __('Set a custom admin login and 404 redirect URL to protect your login page.', 'startertheme') . '</p>';
    }
    
    /** 
     * Add custom fields to the Settings > Permalinks page
     */
    public function addCustomFields()
    {
        foreach ($this->fields as $id => $data) {
            extract($data);
    
            $key = "custom_admin_{$id}_slug";
            $field = function() use ($key, $slug, $description) {
                ?>
                    <input name="<?php echo $key; ?>" type="text" class="regular-text code" value="<?php echo esc_attr(get_option($key)); ?>" placeholder="<?php echo $slug; ?>" /><br />
                    <?php if (isset($description)): ?>
                        <small><?php echo $description; ?></small>
                    <?php endif; ?>
                <?php
            };
    
            add_settings_field($key, $title, $field, 'permalink', 'custom_admin_login_section');
        }
    }
    
    /**
     * Redirect to the actual login page
     */
    function redirectToActualLogin()
    {
        $login = get_option('custom_admin_login_slug');
        $urlString = str_replace('/', '', sanitize_text_field($_SERVER['REQUEST_URI']));
        if ($login && $urlString && $urlString == $login) {
            wp_safe_redirect(home_url("wp-login.php?$login&redirect=false"));
            exit();
        }
    }
    
    /**
     * Redirect to the custom 404 page
     */
    function adminFailedRedirect()
    {
        $login = get_option('custom_admin_login_slug');
        if ($login && strpos($_SERVER['REQUEST_URI'], $login) === false) {
    
            // Get custom redirect URL
            $redirect = get_option('custom_admin_redirect_slug', '404');
            wp_safe_redirect(home_url($redirect), 302);
            exit;
        }
    }
    
    /**
     * Update custom slug fields
     */
    public function updateData()
    {
        foreach (array_keys($this->fields) as $id) {
            $key = "custom_admin_{$id}_slug";
            if (isset($_POST[$key])) {
    
                // Clean the slug 
                $slug = trim($_POST[$key], '/');
                $slug = strtolower($slug);
                $slug = preg_replace("/[^a-zA-Z0-9\/-]/", '', $slug);
                $slug = str_replace('/', '', $slug);
    
                \update_option($key, $slug);
            }
        }
    }
}
