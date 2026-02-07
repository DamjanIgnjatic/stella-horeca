<?php
/**
 * @file
 * use BoldizArt\WpTheme\Captcha;
 */
namespace BoldizArt\WpTheme;

class Captcha
{
    /** @var bool $siteKey; */
    static $enabled; 

    /** @var string $siteKey; */
    static $siteKey; 

    /** @var string $secretKey; */
    static $secretKey; 

    /**
     * Class constructor
     */
    public function __construct()
    {
        \add_action('wp_enqueue_scripts', [$this, 'rcScripts']);
        \add_action('wp_footer', [$this, 'captchaScript']);

        // Load the setup
        self::load();
    }

    /**
     * Add scripts to the footer
     */
    public function rcScripts()
    {
        wp_register_script('rc-script', 'https://www.google.com/recaptcha/api.js?render='.self::$siteKey, [], '1.0.0');
        wp_enqueue_script('rc-script');
    }

    /**
     * Add script to footer
     */
    public function captchaScript()
    {
        ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var rcResponses = document.querySelectorAll('input[name="rc-response"]');
                    if (rcResponses && rcResponses.length) {
                        rcResponses.forEach(rcResponse => {
                            if (rcResponse) {
                                var sitekey = rcResponse.getAttribute('data-sitekey'), 
                                    submitBtn = rcResponse.parentNode.parentNode.querySelector('[type="submit"]');
                                if (sitekey) {
                                    var rcInterval = setInterval(function () {
                                        if (window.grecaptcha) {
                                            grecaptcha.execute(sitekey, {action: 'contact'})
                                                .then(function (token) {
                                                    if (submitBtn) {
                                                        submitBtn.removeAttribute('disabled');
                                                    }
                                                    rcResponse.value = token;
                                                });
                                            clearInterval(rcInterval);
                                        }
                                    }, 500);
                                }
                            }
                        });
                    }
                });
            </script>
        <?php 
    }

    /**
     * Reload the values
     */
    public static function load()
    {
        // Get keys
        self::$enabled = \get_field('enable_recaptcha', 'option') ? true : false;
        self::$siteKey = \get_field('site_key', 'option') ?: '6LeM3-AqAAAAALINvV4iDg0V4SWHdbiaCBahZ-8q';
        self::$secretKey = \get_field('secret_key', 'option') ?: '6LeM3-AqAAAAANgJi_onhtxj26HnrA6gXwuCKKA2';
    }

    /**
     * Create crf by user id or for the current user
     * @return string
     */
    public static function create()
    {
        self::load();
        return self::$siteKey && self::$enabled ? '<input type="hidden" name="rc-response" value="" data-sitekey="'.self::$siteKey.'" />' : '';
    }

    /**
     * Create crf by user id or for the current user
     * @param float $score
     * @return bool
     */
    public static function verify(float $score = 0.5)
    {
        // Check for sitekey
        self::load();
        if (!self::$enabled) {
            return true;
        }
        $rcResponse = array_key_exists('rc-response', $_POST) && $_POST['rc-response'] ? $_POST['rc-response'] : false;

        // ReCaptcha validation
        $data = [
            'secret' => self::$secretKey,
            'response' => $rcResponse
        ];

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($verify);
        $payload = json_decode($result);

        if ($payload->success && $payload->score >= $score) {
            return true;
        }

        return false;
    }
}
