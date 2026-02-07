<?php
/**
 * use BoldizArt\WpTheme\CookieConsent;
 */
namespace BoldizArt\WpTheme;

class CookieConsent
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('init', [$this, 'requestHandler'], 999);

            // Add cookie script
            \add_action('wp_enqueue_scripts', [$this, 'addScripts']);

            // WP footer function
            \add_action('wp_footer', [$this, 'wpFooter']);
        }
    }

    /**
     * Check for any cookie consent request
     */
    public function requestHandler()
    {
        if (array_key_exists('cookie-consent-scripts', $_POST) && $_POST['cookie-consent-scripts'] === 'df54s69r7e8tKH') {
            $cookieConsentScripts = get_field('cookie_consent_scripts', 'option');

            // Set response
            $response = [];
            if ($cookieConsentScripts && is_array($cookieConsentScripts)) {
                foreach ($cookieConsentScripts as $cookieConsentScript) {
                    $response[$cookieConsentScript['type']] = $cookieConsentScript['urls'];
                }
            }

            header('Content-Type: application/json');
            die(json_encode([
                'data' => $response
            ]));
        }
    }

    /**
     * Add scripts
     */
    public function addScripts()
    {
        if (function_exists('wp_register_script') && function_exists('wp_enqueue_script') && function_exists('get_template_directory_uri')) {
            // Default script
            if (get_field('enable_cookie_consent', 'option')) {
                \wp_register_script('cookie-consent-script', \get_template_directory_uri() . '/dist/js/cookie-consent.js', [], ASSETS_VERSION);
                \wp_enqueue_script('cookie-consent-script');
            }
        }
    }

    /**
     * Inject content into the footer of the website
     */
    public function wpFooter()
    {
        // Add the cookie consent modal into the sites footer
        if (function_exists('get_field') && get_field('enable_cookie_consent', 'option') && $cookieConsent = get_field('cookie_consent', 'option')): ?>
            <?php if (is_array($cookieConsent) && !empty($cookieConsent)): ?>
                <div class="small-cookie-consent d-none" id="smallCookieConsent">
                    <div class="content-wrapper p-3 p-lg-4">
                        <?php if (array_key_exists('message', $cookieConsent) && $message = $cookieConsent['message']): ?>
                            <div class="message small"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <div class="d-lg-flex justify-content-between">
                            <?php if (array_key_exists('manage_cookies_button_label', $cookieConsent) && $selectedBtn = $cookieConsent['manage_cookies_button_label']): ?>
                                <button type="button" class="btn btn-first px-3 me-lg-2 my-2 js-manage-cookies w-100">
                                    <small><?php echo $selectedBtn; ?></small>
                                </button>
                            <?php endif; ?>
                            <?php if (array_key_exists('accept_all_button_label', $cookieConsent) && $allBtn = $cookieConsent['accept_all_button_label']): ?>
                                <button type="button" class="btn btn-second px-3 ms-lg-2 my-2 js-accept-cookies w-100">
                                    <small><?php echo $allBtn; ?></small>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="cookie-consent d-none justify-content-center align-iteml-center" id="cookieConsent">
                    <div class="content-wrapper p-3 p-lg-5">
                        <div class="copy">
                            <?php if (array_key_exists('title', $cookieConsent) && $title = $cookieConsent['title']): ?>
                                <h3 class="header"><?php echo $title; ?></h3>
                            <?php endif; ?>
                            <?php if (array_key_exists('description', $cookieConsent) && $description = $cookieConsent['description']): ?>
                                <div class="message"><?php echo $description; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="content">
                            <?php foreach ($cookieConsent['content'] as $content): ?>
                                <div class="item">
                                    <div class="form-check form-switch d-flex justify-content-between ps-0">
                                        <div class="left d-flex">
                                            <div class="arrow pt-2"></div>
                                            <label class="form-check-label" for="<?php echo $content['type']; ?>">
                                                <strong><?php echo $content['label']; ?></strong>
                                            </label>
                                        </div>
                                        <div class="d-inline-block">
                                            <input class="form-check-input d-inline-block" name="<?php echo $content['type']; ?>" type="checkbox" id="<?php echo $content['type']; ?>" role="switch">
                                        </div>
                                    </div>

                                    <div class="description small"><hr /><?php echo $content['description']; ?></div>
                                    <div class="text-danger small error d-none"><?php echo array_key_exists('reject_message', $cookieConsent) && $content['type'] == 'functional' ? $cookieConsent['reject_message'] : ''; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-lg-flex justify-content-between pt-4">
                            <div class="d-flex pb-3 pb-lg-0">
                                <?php if (array_key_exists('accept_all_button_label', $cookieConsent) && $allBtn = $cookieConsent['accept_all_button_label']): ?>
                                    <button type="button" class="accept btn btn-first px-3 px-lg-4 me-2 me-lg-0 js-accept-cookies">
                                        <small><?php echo $allBtn; ?></small>
                                    </button>
                                <?php endif; ?>
                                <?php if (array_key_exists('reject_all_button_label', $cookieConsent) && $allBtn = $cookieConsent['reject_all_button_label']): ?>
                                    <button type="button" class="reject btn btn-first px-3 px-lg-4 ms-2 js-reject-cookies">
                                        <small><?php echo $allBtn; ?></small>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if (array_key_exists('manage_cookies_button_label', $cookieConsent) && $selectedBtn = $cookieConsent['save_settings_button_label']): ?>
                                <button type="button" class="save btn btn-second px-3 px-lg-4 ms-0 ms-lg-2 js-selected-cookies">
                                    <small><?php echo $selectedBtn; ?></small>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif;
    }
}
