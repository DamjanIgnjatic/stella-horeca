<?php
/**
 * use BoldizArt\WpTheme\URL;
 */
namespace BoldizArt\WpTheme;

class URL
{
    /**
     * Class constructor
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function create(string $name, array $params = []) : string 
    {
        switch ($name) {
            case 'login':
                $pid = get_option('login_page');
                $url = $pid ? \get_permalink($pid): \home_url() . '/logovanje/';
                break;
            
            case 'register':
                $pid = get_option('register_page');
                $url = $pid ? \get_permalink($pid): \home_url() . '/registracija/';
                break;

            case 'profile':
            case 'dashboard':
                $pid = get_option('profile_page');
                $url = $pid ? \get_permalink($pid): get_permalink(get_option('woocommerce_myaccount_page_id'));
                break;

            case 'lost-password':
                $pid = get_option('lost_password_page');
                $url = $pid ? \get_permalink($pid) :  \home_url() . '/izgubljena-lozinka/';
                break;

            case 'reset':
            case 'password-reset':
            case 'recover':
                $pid = get_option('password_reset_page');
                $url = $pid ? \get_permalink($pid) : \home_url() . '/povratak-lozinke/';
                break;

            default:
                $url = \home_url();
                break;
        }

        if (count($params)) {
            $url .= '?' .http_build_query($params);
        }

        return $url;
    }
}
