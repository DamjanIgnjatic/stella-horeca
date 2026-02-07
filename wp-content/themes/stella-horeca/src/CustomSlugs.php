<?php
/**
 * use BoldizArt\WpTheme\CustomSlugs;
 */
namespace BoldizArt\WpTheme;

class CustomSlugs
{
    /** @param array $fields */
    public $fields = [];

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {
            
            // Init function
            \add_action('admin_init', [$this, 'addFields']);
            \add_action('admin_init', [$this, 'updateSlugs']);
            \add_action('init', [$this, 'rewriteRules']);
        }

        // Add filters
        if (function_exists('add_filter')) {
            add_filter('register_post_type_args', [$this, 'changeSlugs'], 10, 2);
        }
    }

    /**
     * Add custom fields to the Settings > Permalinks page
     */
    public function addFields()
    {
        foreach ($this->fields as $id => $title) {
            $key = "custom_{$id}_slug";
            $field = function() use ($key) {
                ?>
                    <input name="<?php echo $key; ?>" type="text" class="regular-text code" value="<?php echo esc_attr(\get_option($key)); ?>" placeholder="<?php echo str_replace(['custom_', '_slug'], '', $key); ?>" />
                <?php
            };
            
            \add_settings_field($key, $title, $field, 'permalink', 'optional');
        }
    }

    /**
     * Update custom slug fields
     */
    public function updateSlugs()
    {
        foreach (array_keys($this->fields) as $id) {
            $key = "custom_{$id}_slug";
            if (isset($_POST[$key])) {
    
                // Clean the slug 
                $slug = trim($_POST[$key], '/');
                $slug = strtolower($slug);
                $slug =  preg_replace("/[^a-zA-Z0-9\/-]/", '', $slug);
                $slug = str_replace('//', '', $slug);
    
                \update_option($key, $slug);
            }
        }
    }


    /**
     * Change slugs of custom post types
     * @param array $args
     * @param string $postType 
     */
    public function changeSlugs($args, $postType)
    {
        foreach (array_keys($this->fields) as $id) {
            // Check the post type
            if ($postType == $id) {

                // Check for options
                $key = "custom_{$id}_slug";
                $slug = esc_attr(get_option($key, true));
                if ($slug && strlen($slug) > 2) {
                    $args['rewrite']['slug'] = $slug;
                }
            }
        }

        return $args;
    }

    /**
     * URL rewrite rules
     */
    public function rewriteRules()
    {
        foreach (array_keys($this->fields) as $id) {

            // Check for options
            $key = "custom_{$id}_slug";
            $slug = esc_attr(get_option($key, true));
            if ($slug && strlen($slug) > 2) {
                \add_rewrite_rule('^' . $slug . '/([^/]*)/?', 'index.php?post_type=' . $id . '&name=$matches[1]', 'top');
            }
        }

        flush_rewrite_rules();
    });
}
