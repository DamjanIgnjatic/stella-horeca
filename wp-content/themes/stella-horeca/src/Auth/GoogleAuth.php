<?php
/**
 * @file
 * use BoldizArt\WpTheme\Auth\GoogleAuth;
 */
namespace BoldizArt\WpTheme\Auth;

/*
Copyright 2020 BoldizArt

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
use Google_Client;
use Google_Service_Oauth2;
use BoldizArt\WpTheme\URL;

class GoogleAuth extends Auth
{
    /** @var bool $enabled; */
    protected $enabled;

    /** @param string $clientId */
    protected $clientId;

    /** @param string $clientSecret */
    protected $clientSecret;

    /** @param string $redirectUri */
    protected $redirectUri;

    /** @param \Google_Client $client */
    protected $client;

    /** @param string $redirect */
    protected $redirect;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // Configuration init
        $this->enabled = true;
        $this->clientId = '728576876208-dctffesnuej7lv0m32i4takgv734pvol.apps.googleusercontent.com';
        $this->clientSecret = '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';
        $this->redirectUri = URL::create('dashboard');

        // create Client Request to access Google API
        $this->client = new Google_Client();
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($this->redirectUri);
        $this->client->addScope('email');
        $this->client->addScope('profile');

        // Init function
        add_action('init', [$this, 'detectRegistrationRequest']);

        // Add shortcodes
        add_shortcode('google_login', [$this, 'LoginButton']);
    }
    
    // Detect login request
    public function detectRegistrationRequest()
    {
        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (array_key_exists('access_token', $token)) {
                $this->client->setAccessToken($token['access_token']);
                
                // get profile info
                $google_oauth = new Google_Service_Oauth2($this->client);
                $google_account_info = $google_oauth->userinfo->get();
                $email = $google_account_info->email;
                $fullname = $google_account_info->name;

                // Load user by email
                $user = get_user_by('email', $email);
                if (!$user) {
                    $uid = $this->create($this->createUsername($email), $email, uniqid(), [
                        'email_verified' => true,
                        'register_type' => 'Google'
                    ]);

                    if ($uid) {
                        // Add display name
                        $namearr = explode(' ', $fullname);
                        $firstname = array_shift($namearr);
                        $lastname = is_array($namearr) && count($namearr) ? implode(' ', $namearr) : '';
                        wp_update_user([
                            'ID' => $uid, 
                            'display_name' => $fullname,
                            'first_name' => $firstname,
                            'last_name' => $lastname
                        ]);

                        // Update user meta
                        update_user_meta($uid, 'terms_accepted', true);

                        $this->login($uid, URL::create('dashboard'));
                    }
                } else {
                    $this->login($user->ID, URL::create('dashboard'));
                }
            }
        }
    }

    /**
     * Show Google button
     * @param string|array $attr
     */
    public function LoginButton($attr)
    {
        // Get the current user
        $user = \wp_get_current_user();

        // Set label 
        $label = __('Continue with Google', 'startertheme');

        // Get type
        if ($attr && is_array($attr) && array_key_exists('type', $attr) && $type = $attr['type']) {
            switch ($type) {
                case 'login':
                    $label = __('Login with Google', 'startertheme');
                    break;

                case 'register':
                    $label = __('Register with Google', 'startertheme');
                    break;
            }
        }

        if ((!$user || !$user->ID) && !isset($_GET['code'])) {
            return '
                <a href="'.$this->client->createAuthUrl().'" class="btn btn-google my-2 my-lg-0">
                    <img src="'.\get_template_directory_uri() . '/img/google.png" class="google-logo"> ' . $label . '</a>
            ';
        }
    }
}
