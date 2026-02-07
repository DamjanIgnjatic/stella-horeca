<?php
/**
 * use BoldizArt\WpTheme\AcfSupport;
 */
namespace BoldizArt\WpTheme;

class AcfSupport
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Block support
            add_action('init', [$this, 'registerCustomBlocks']);

            // Option page
            add_action('acf/init', [$this, 'addOptionsPage']);

            // Add block image
            add_action('block_image', [$this, 'blockImage'], 10, 1);
        }

        // Add filters
        if (function_exists('add_filter')) {

            // Block support
            add_filter('allowed_block_types_all', [$this, 'disableDefaultGutenbergBlocks']);

            // ACF save points
            add_filter('acf/settings/save_json', [$this, 'acfJsonSavePoint']);
            add_filter('acf/settings/load_json', [$this, 'acfJsonLoadPoint']);

            // Prevent auto update
            add_filter('site_transient_update_plugins', [$this, 'preventAutoUpdate']);
        }
    }

    /** 
     * Register block scripts and styles
     */
    public function registerCustomBlocks()
    {
        $blocks = array_filter(glob(__DIR__.'/../blocks/*'), 'is_dir');
        $blockSlugs = [];

        foreach ($blocks as $block) {
            \register_block_type($block);
            $sections = explode('/', $block);
            $name = end($sections);
            $blockSlugs[] = 'acf/'.$name;
            $this->createBlockFieldGroup($name);
            \wp_register_style("{$name}-block-style", get_template_directory_uri()."/dist/css/blocks/{$name}/{$name}.css", ASSETS_VERSION);
            \wp_register_script("{$name}-block-script", get_template_directory_uri()."/dist/js/blocks/{$name}/{$name}.js", ['acf'], ASSETS_VERSION);
        }

        $this->deleteFieldGroups($blockSlugs);
    }

    /**
     * Add ACF JSON save point
     * @param string $path
     */
    public function acfJsonSavePoint($path) {
        // Update path
        $path = get_stylesheet_directory().'/acf-json';

        // Return path
        return $path;
    }

    /**
     * Add ACF JSON load point
     * @param array $paths
     */
    public function acfJsonLoadPoint($paths)
    {
        // Remove the original path
        unset($paths[0]);

        // Update path
        $paths[] = get_stylesheet_directory().'/acf-json';

        // Return path
        return $paths;
    }

    /**
     * Add ACF option page
     */
    public function addOptionsPage()
    {
        if (function_exists('acf_add_options_page')) {
        
            acf_add_options_page([
                'page_title' 	=> 'Theme options page',
                'menu_title'	=> 'Theme options',
                'menu_slug' 	=> 'theme-options',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ]);

            acf_add_options_sub_page([
                'page_title' 	=> 'Social media links',
                'menu_title'	=> 'Social media links',
                'parent_slug'	=> 'theme-options',
            ]);

            acf_add_options_sub_page([
                'page_title' 	=> 'Cookie consent',
                'menu_title'	=> 'Cookie consent',
                'parent_slug'	=> 'theme-options',
            ]);

            acf_add_options_sub_page([
                'page_title' 	=> 'Dynamic modals',
                'menu_title'	=> 'Dynamic modals',
                'parent_slug'	=> 'theme-options',
            ]);

            acf_add_options_sub_page([
                'page_title' 	=> 'Footer section',
                'menu_title'	=> 'Footer section',
                'parent_slug'	=> 'theme-options',
            ]);
        }
    }

    /**
     * Hide the update message for the ACF pro plugin
     */
    public function preventAutoUpdate($value) {
        if (is_object($value) && isset($value->response) && is_array($value->response)) {
            if (array_key_exists('advanced-custom-fields-pro/acf.php', $value->response)) {
                unset($value->response['advanced-custom-fields-pro/acf.php']);
            }
        }

        return $value;
    }

    /**
     * Creeate a new ACF block field group
     * @param string $name
     */
    public function createBlockFieldGroup(string $name)
    {
        // Create block field group
        $blocks = \get_posts([
            'title' => "Block - {$name}",
            'post_type' => 'acf-field-group',
        ]);

        if (!count($blocks)) {

            $groupContent = [
                'location' => [
                    '0' => [
                        '0' => [
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/'.$name
                        ]
                    ]
                ],
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'description' => '',
                'show_in_rest' => 0
            ];
            
            $args = [
                'post_date' => date('Y-m-d H:i:s'),
                'post_content' => serialize($groupContent),
                'post_title' => 'Block - '.$name,
                'post_excerpt' => $name,
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_name' => 'group_' . uniqid(),
                'post_type' => 'acf-field-group'
            ];

            \wp_insert_post($args);
        }
    }

    /**
     * Delete field groups of deleted block
     * @param array $slugs
     */
    public function deleteFieldGroups(array $slugs)
    {
        // Fetch all groups
        $args = [
            'post_type' => 'acf-field-group',
            'posts_per_page' => -1
        ];
        $groups = \get_posts($args);

        // Check if there any deleted post
        if ($groups && is_array($groups)) {
            foreach ($groups as $group) {
                $content = unserialize($group->post_content);
                if (substr($group->post_title, 0, 7) === "Block - " && isset($content['location'][0][0]['value'])) {
        
                    // Delete the field group if the depended block has deleted
                    if (!in_array($content['location'][0][0]['value'], $slugs)) {
                        \wp_trash_post($group->ID);
                    }
                }
            }
        }
    }

    /**
     * Disable default gutenberg blocks on pages
     */
    public function disableDefaultGutenbergBlocks()
    {
        global $pagenow;
        $exceptions = [
            // 'page',
            'post',
            'widgets.php',
            'customize.php',
        ];

        // Disable the default Gutenberg blocks
        if (!in_array($pagenow, $exceptions) && !in_array(\get_post_type(), $exceptions)) {

            global $block_post_type_slugs;
            $postType = \get_post_type();

            if (isset($block_post_type_slugs[$postType]) && $block_post_type_slugs[$postType]) {
                return $block_post_type_slugs[$postType];
            } else {
                global $block_slugs;
                return $block_slugs;
            }
        }
    }

    /**
     * Create an url to the block image
     * @param string $filePath
     * @return string
     */
    function blockImage($filePath)
    {
        $root = $_SERVER['DOCUMENT_ROOT'];
        $path = str_replace($root, '', $filePath);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('.php', '.png', $path);
        $url = home_url($path);
    
        echo '<img src="'.$url.'" style="width:100%; height:auto;">';
    }
}
