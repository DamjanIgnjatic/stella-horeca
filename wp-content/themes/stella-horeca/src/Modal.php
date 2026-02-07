<?php
/**
 * use BoldizArt\WpTheme\Modal;
 */
namespace BoldizArt\WpTheme;

class Modal
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Add modal
            \add_action('wp_footer', [$this, 'addModal']);
        }
    }

    /**
     * Add modal content to the footer
     */
    public function addModal()
    {
        // Get the modal content from the request if exists
        $selector = 'default';
        $title = array_key_exists('msgTitle', $_REQUEST) && !empty($_REQUEST['msgTitle']) ? $_REQUEST['msgTitle'] : false;
        $body = array_key_exists('msg', $_REQUEST) && !empty($_REQUEST['msg']) ? $_REQUEST['msg'] : false;
        $id = 'starterthemeModal';

        echo $this->createModal($title, $body, $selector, $id);
    }

    /**
     * Create modal
     * @param string $title
     * @param string $body
     * @param string $seletor
     * @param string $id
     */
    public function createModal($title, $body, $selector = 'default', $id = 'starterthemeModal')
    {
        return '
            <div class="theme-modal '.$selector.'" id="'.$id.'">
                <div class="theme-modal-container">
                    <div class="modal-close d-flex justify-content-center align-items-center"></div>
                    <div class="theme-modal-body">
                        <h3 class="modal-title '.(!$title ? 'hidden' : '').'">'.$title.'</h3>
                        <div class="modal-text '.(!$body ? 'hidden' : '').'">'.$body.'</div>
                    </div>
                </div>
            </div>
        ';
    }
}
