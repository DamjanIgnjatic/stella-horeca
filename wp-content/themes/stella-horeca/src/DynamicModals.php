<?php
/**
 * use BoldizArt\WpTheme\DynamicModals;
 */
namespace BoldizArt\WpTheme;

class DynamicModals
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Add content to the footer
            add_action('wp_footer', [$this, 'renderDynamicModal']);
        }
    }

    /**
     * Inject content into the footer of the website
     */
    Function renderDynamicModal()
    {
        // Add the cookie consent modal into the sites footer
        if (function_exists('get_field') && $dynamicModals = get_field('dynamic_modals', 'option')) {
            if (is_array($dynamicModals) && count($dynamicModals)) {
                foreach ($dynamicModals as $key => $dynamicModal) {
                    if ($this->dynamicModalIsVisible($dynamicModal)) {
                        wp_enqueue_script('dynamic-modals-script');

                        $pid = get_queried_object_id();
                        $vertical = array_key_exists('vertical_align', $dynamicModal) ? $dynamicModal['vertical_align'] : 'center';
                        $horizontal = array_key_exists('horizontal_align', $dynamicModal) ? $dynamicModal['horizontal_align'] : 'center';
                        ?>
                        <div class="theme-modal js-modals p-3 p-lg-5 justify-content-<?php echo $horizontal; ?> align-items-<?php echo $vertical; ?>"  
                            id="<?php echo "modalNo{$key}{$pid}"; ?>" 
                            <?php if (array_key_exists('appear', $dynamicModal) && $appear = $dynamicModal['appear']): ?>
                                data-type="<?php echo $appear; ?>" 
                            <?php endif; ?>
                            <?php if (array_key_exists('value', $dynamicModal) && $value = $dynamicModal['value']): ?>
                                data-value="<?php echo $value; ?>"
                            <?php endif; ?>
                            <?php if (array_key_exists('max_display_count', $dynamicModal) && $displayCount = $dynamicModal['max_display_count']): ?>
                                data-display-count="<?php echo $displayCount; ?>"
                            <?php endif; ?>
                        >
                            <div class="theme-modal-container">
                                <div class="modal-close d-flex justify-content-center align-items-center"></div>
                                <div class="theme-modal-body">
                                    <?php if (array_key_exists('title', $dynamicModal) && $title = $dynamicModal['title']): ?>
                                        <h3 class="modal-title"><?php echo $title; ?></h3>
                                    <?php endif; ?>
                                    <?php if (array_key_exists('text', $dynamicModal) && $text = $dynamicModal['text']): ?>
                                        <div class="modal-text"><?php echo $text; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }
            }
        }
    }

    /**
     * Check if the dynamic modal should be displayed
     * @param array $dynamicModal
     */
    function dynamicModalIsVisible($dynamicModal)
    {
        // Check if the dynamic modal is enables
        if (!array_key_exists('enabled', $dynamicModal) || !$dynamicModal['enabled']) {
            return false;
        }

        // Get the current page id
        $pid = \get_queried_object_id();

        // Check for include categories and pages
        if (array_key_exists('include', $dynamicModal) && $dynamicModal['include']) {

            // Pages
            if (array_key_exists('pages', $dynamicModal) && $pages = $dynamicModal['pages']) {
                if (is_array($pages) && count($pages)) {
                    if (!in_array($pid, $pages)) {
                        return false;
                    }
                }
            }

            // Post types
            if (array_key_exists('post_types', $dynamicModal) && $postTypes = $dynamicModal['post_types']) {
                if (is_array($postTypes) && count($postTypes)) {
                    if (!in_array(get_post_type($pid), $postTypes)) {
                        return false;
                    }
                }
            }
        }

        // Check for exclude categories and pages
        if (array_key_exists('exclude', $dynamicModal) && $dynamicModal['exclude']) {

            // Pages
            if (array_key_exists('exclude_pages', $dynamicModal) && $pages = $dynamicModal['exclude_pages']) {
                if (is_array($pages) && count($pages)) {
                    if (in_array($pid, $pages)) {
                        return false;
                    }
                }
            }

            // Post types
            if (array_key_exists('exclude_post_types', $dynamicModal) && $postTypes = $dynamicModal['exclude_post_types']) {
                if (is_array($postTypes) && count($postTypes)) {
                    if (in_array(get_post_type($pid), $postTypes)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
