<?php
/**
 * use BoldizArt\WpTheme\SendFox;
 */
namespace BoldizArt\WpTheme;

class SendFox
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {
            
            // Script equeue function
            \add_action('wp_enqueue_scripts', [$this, 'endfoxScriptRegister']);
        }

        // Add shortcodes
        if (function_exists('add_shortcode')) {

            // Register shortcode
            \add_shortcode('sendfox_subscribe', [$this, 'sendfoxSubscribeForm']);
            \add_shortcode('sendfox_subscribe_slim', [$this, 'sendfoxSubscribeFormSlim']);
        }
    }

    /**
     * Register Sendfox script
     */
    function endfoxScriptRegister()
    {
        wp_register_script('sendfox-script', 'https://sendfox.com/js/form.js');
    }

    /**
     * Register a shortcode for the subscribe form
     * @shortcoode [sendfox_subscribe]
     */
    function sendfoxSubscribeForm()
    {
        wp_enqueue_style('sendfox-script');

        $form = '
            <form method="post" action="https://sendfox.com/form/1x9jpy/1vqkyd" class="sendfox-form" id="1vqkyd" data-async="true" data-recaptcha="false">
                <div class="mb-3">
                    <label class="form-label" for="sendfox_form_name">Vaše ime </label>
                    <input type="text" name="first_name" id="sendfox_form_name" class="form-control" placeholder="Vaše ime">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="sendfox_form_email">Vaša email adresa </label>
                    <input type="email" name="email" id="sendfox_form_email" class="form-control" placeholder="Vaša email adresa" required>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="gdpr" value="1" id="agreeWithTerms" required>
                        <label class="form-check-label mt-1" for="agreeWithTerms">Slažem se sa uslovima korišćenja.</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Prijavi se</button>
                <!-- no botz please -->
                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="a_password" tabindex="-1" value="" autocomplete="off" /></div>
            </form>
        ';

        return $form;
    }

    /**
     * Register a shortcode for the subscribe form
     * @shortcoode [sendfox_subscribe_slim]
     */
    function sendfoxSubscribeFormSlim()
    {
        wp_enqueue_style('sendfox-script');

        $form = '
            <form method="post" action="https://sendfox.com/form/1x9jpy/3e86ol" class="sendfox-form" id="3e86ol" data-async="true" data-recaptcha="false">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Vaša email adresa" id="sendfox_form_email" style="border-radius: 50px 0 0 50px" aria-label="Vaša email adresa" aria-describedby="sendfoxEmailAddress" required>
                    <button class="btn btn-primary" type="submit" id="sendfoxEmailAddress">Pošalji</button>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="gdpr" value="1" id="agreeWithTerms" required>
                        <label class="form-check-label mt-1" for="agreeWithTerms">Slažem se sa uslovima korišćenja.</label>
                    </div>
                </div>
                <!-- no botz please -->
                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="a_password" tabindex="-1" value="" autocomplete="off" /></div>
            </form>
        ';

        return $form;
    }
}
