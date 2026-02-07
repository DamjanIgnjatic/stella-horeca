<?php
/**
 * @file
 * use BoldizArt\WpTheme\Auth\Auth;
 */
namespace BoldizArt\WpTheme\Auth;

use WP_User;
use BoldizArt\WpTheme\URL;

/*
Copyright 2020 BoldizArt

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
class Auth
{
    /** @param Object $errors */
    public $errors = null;
  
    /** @param String $message */
    public $message = null;

    /**
     * Create user programatically
     * @param string $username
     * @param string $email
     * @param string $password
     * @param array $meta
     */
    public function create(string $username, string $email, string $password, array $meta = [])
    {
        // create user
        $id = \wp_create_user($username, $password, $email);
        if (!\is_wp_error($id)) {
            // Update user meta
            \update_user_meta($id, 'programmatically', 1);
            foreach ($meta as $name => $value) {
                \update_user_meta($id, $name, $value);
            }
    
            return $id;
        }

        return false;
    }
    
    /**
     * Authenticate the user
     * @param string $user;
     * @param string $password;
     * @param bool $allowAdminLogin
     */
    public function authenticate(string $username, string $password, $allowAdminLogin = false)
    {
        $user = \wp_authenticate($username, $password);
        if ($user && $user instanceof WP_User) {

            // Prevent admin login
            if (!empty($user->roles) && is_array($user->roles) && in_array('administrator', $user->roles) && !$allowAdminLogin) {
                return false;
            }

            return $user;
        }

        return false;
    }

    /**
     * User login programatically
     * @param int $uid
     * @param string $redirect
     * @param bool $allowAdminLogin
     */
    public function login(int $id, $redirect = '')
    {
        $redirect = $redirect ?: URL::create('dashboard');
        \wp_clear_auth_cookie();
        \wp_set_current_user($id);
        \wp_set_auth_cookie($id);
        \wp_safe_redirect($redirect, 302);
        echo "<script>document.location.href='{$redirect}';</script>";
        exit;
    }

    /**
     * Logout the current user
     * @param string $redirect
     */
    public function logout($redirect = '')
    {
        \wp_destroy_current_session();
        \wp_clear_auth_cookie();

        if ($redirect) {
            \wp_safe_redirect($redirect);
            echo "<script>document.location.href='{$redirect}';</script>";
            exit;
        }
    }

    /**
     * Redirect the user
     * @param string $redirect
     */
    public function redirect($redirect = '')
    {
        if ($redirect) {
            \wp_safe_redirect($redirect);
            echo "<script>document.location.href='{$redirect}';</script>";
            exit;
        }
    }

    /**
     * @todo - Password request validation
     * @param string $validationString
     * @param string $email
     * @param string $password
     * @param string $redirect
     */
    public function resetPassword(string $validationString, string $email, string $password, string $redirect = '')
    {
        if ($email && $validationString && filter_var($email, FILTER_SANITIZE_EMAIL)) {

            // Fetch the verification data
            $validUid = $this->verifyToken($validationString);
            if ($validUid) {
                // Change password
                wp_set_password($password, $validUid);

                // Re-login this user
                $redirect = $redirect ?: URL::create('dashboard');
                $this->login($validUid, $redirect);
                return true;
            }
        }

        return false;
    }

    /**
     * Create username by email address
     * @param string $email
     */
    public function createUsername(string $email)
    {
        $username = str_replace(['-', ' ', '.'], '_', substr($email, 0, strrpos($email, '@')));
        while (get_user_by('username', $username)) {
            $username .= '_';
        }

        return $username;
    }

    /**
     * Create an unique token
     * @param string $email, 
     * @param int $expireInDays
     */
    public function createToken(string $email, int $expireInDays = 1, $name = 'verification_token')
    {
        $token = false;
        if ($email && filter_var($email, FILTER_SANITIZE_EMAIL)) {

            // Load user by email
            $user = get_user_by('email', $email);
            if ($user && $user->ID) {
                $data = [
                    'email' => $email,
                    'token' => uniqid('startertheme', $email),
                    'expire' => \strtotime("+{$expireInDays} day"),
                ];
                $serialized = serialize($data);
                $token = base64_encode($serialized);

                // Update the user token
                update_user_meta($user->ID, $name, $token);
            }
        }

        return $token;
    }

    /**
     * Create an unique token
     * @param string $token
     */
    public function verifyToken($token, $name = 'verification_token')
    {
        $data = unserialize(base64_decode($token));
        if (is_array($data) && isset($data['email'], $data['token'], $data['expire'])) {
            $email = $data['email'];
            $token = $data['token'];
            $expire = $data['expire'];

            if (\time() > $expire) {
                return false;
            }

            if ($email && filter_var($email, FILTER_SANITIZE_EMAIL)) {
    
                // Load user by email
                $user = get_user_by('email', $email);
                if ($user && $user->ID) {

                    // Check the user token
                    $originalData = $data = unserialize(base64_decode(get_user_meta($user->ID, $name, true)));
                    if (is_array($originalData) && isset($originalData['email'], $originalData['token'], $originalData['expire'])) {
                        $originalToken = $originalData['token'];
                        $originalExpire = $originalData['expire'];
            
                        if (\time() > $originalExpire) {
                            return false;
                        }

                        if ($token == $originalToken) {
                            delete_user_meta($user->ID, $name);
                            return $user->ID;
                        }

                    }

                }
            }
        }

        return false;
    }
}
