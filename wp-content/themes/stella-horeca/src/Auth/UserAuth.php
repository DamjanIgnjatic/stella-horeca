<?php
/**
 * use BoldizArt\WpTheme\Auth\UserAuth;
 */
namespace BoldizArt\WpTheme\Auth;

use BoldizArt\WpTheme\URL;
use BoldizArt\WpTheme\Chaptcha;

class UserAuth extends Auth
{
  /** @param Object $errors */
  public $errors = null;

  /** @param String $message */
  public $message = null;

  /**
   * Class constructor
   */
  public function __construct()
  {
    // Set message
    if (array_key_exists('message', $_GET) && $message = $_GET['message']) {
      $this->message = $message;
    }

    // Set error message
    if (array_key_exists('error', $_GET) && $message = $_GET['error']) {
      $this->errors['global'] = $message;
    }

    // Add actions
    if (function_exists('add_action')) {
      // Init function
      \add_action('init', [$this, 'registrationHandler']);
      \add_action('init', [$this, 'loginHandler']);
      \add_action('init', [$this, 'verifyEmail']);
      \add_action('init', [$this, 'logOutListener']);
      \add_action('init', [$this, 'lostPasswordHandler']);
      \add_action('init', [$this, 'resetPasswordHandler']);
    }

    // Add shortcodes
    if (function_exists('add_shortcode')) {
      add_shortcode('register_form', [$this, 'registerForm']);
      add_shortcode('login_form', [$this, 'loginForm']);
      add_shortcode('lost_password_form', [$this, 'lostPasswordForm']);
      add_shortcode('password_reset_form', [$this, 'resetPasswordForm']);
    }
  }

  /**
   * User logout listener
   */
  public function logOutListener()
  {
    if (array_key_exists('logout', $_REQUEST)) {
      $this->logout();
    }
  }

  /**
   * Create a registration form
   * @shortcode [register_form]
   */
  public function registerForm()
  {
    // Check if user is already logged in
    $redirect = URL::create('dashboard');
    if (current_user_can('administrator')) {
      return '<div class="alert alert-success mb-3" role="alert">You are already logged in. <a href="' . $redirect . '" class="text-primary">Click here to open the dashboard.</a><div>';
    } elseif (is_user_logged_in()) {
      return $this->redirect($redirect);
    }

    // Set response
    $response = $this->getMessages();

    // Nonce for security (see explanation below)
    global $wp;
    $nonce = wp_create_nonce('startertheme_registration_nonce');
    $response .= '
      <form method="post" class="register-form" action="' . htmlspecialchars(\home_url($wp->request)) . '">
        <input type="hidden" name="verify" value="'. $nonce .'">
        ' . Chaptcha::create() . '
        <div class="mb-3">
          <label for="fullname">' . __('Full name', 'startertheme') . '</label>
          <input type="text" class="form-control ' . ($this->errors && isset($this->errors['fullname']) ? 'is-invalid' : '') . '" id="fullname" name="fullname" value="' . (isset($_POST['fullname']) ? \sanitize_text_field($_POST['fullname']) : '') . '" required->
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['fullname']) ? $this->errors['fullname'] : '') . '</div>
        </div>
        <div class="mb-3">
          <label for="email">' . __('Email', 'startertheme') . '</label>
          <input type="email" class="form-control ' . ($this->errors && isset($this->errors['email']) ? 'is-invalid' : '') . '" id="email" name="email" value="' . (isset($_POST['email']) ? \sanitize_email($_POST['email']) : '') . '" required->
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['email']) ? $this->errors['email'] : '') . '</div>
        </div>
        <div class="mb-3">
          <label for="password">' . __('Password', 'startertheme') . '</label>
          <input type="password" class="form-control ' . ($this->errors && isset($this->errors['password']) ? 'is-invalid' : '') . '" id="password" name="password" required->
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['password']) ? $this->errors['password'] : '') . '</div>
        </div>
        <div class="mb-3">
          <input type="submit" class="btn btn-first w-100" value="' . __( 'Register', 'startertheme' ) . '">
        </div>
      </form>
    ';

    return $response;
  }

  /**
   * Registration validation
   */
  public function registrationHandler()
  {
    // Check for valid form submission and nonce verification
    if (isset($_POST['verify']) && wp_verify_nonce( $_POST['verify'], 'startertheme_registration_nonce')) {
      // Captcha verification
      if (!Chaptcha::verify()) {
        $this->errors['global'] = __('Google reChaptcha verification failed.', 'startertheme');
        return;
      }

      // Sanitize user data
      $email = sanitize_email($_POST['email']);
      $fullname = sanitize_text_field($_POST['fullname']);
      $fullname = preg_replace('/[^\p{L}\s,]/u', '', $fullname);
      $password = sanitize_text_field($_POST['password']);

      // Validate name
      if (empty($fullname)) {
        $this->errors['fullname'] = __('The full name is required.', 'startertheme');
      } else {
        // Fetch the first and last name
        $namearr = explode(' ', $fullname);
        $firstname = array_shift($namearr);
        $lastname = is_array($namearr) && count($namearr) ? implode(' ', $namearr) : '';
      }

      // // Check for accepted terms
      // $accepted = array_key_exists('terms_accepted', $_POST) ? true : false;
      // if (!$accepted) {
      //   $this->errors['terms_accepted'] = __('You must to accept our terms and conditions if you want to register in this wesite.', 'startertheme');
      // }

      // Validate email
      if (empty($email)) {
        $this->errors['email'] = __('Email is required.', 'startertheme');
      } elseif (get_user_by('email', $email)) {
        $this->errors['email'] = __('The user with this email already registered', 'startertheme');
      }
        
      // Validate password
      if (empty($password)) {
        $this->errors['password'] = __('Password is required.', 'startertheme');
      } elseif (strlen($password) < 8) {
        $this->errors['password'] = __('Password mut be at leaset 8 characthers long.', 'startertheme');
      }
      
      // If no errors, create the user
      if (empty($this->errors)) {

        // Create user
        $uid = $this->create($this->createUsername($email), $email, $password, [
          'terms_accepted' => true
        ]);

        // User created successfully, redirect or display confirmation message
        if ($uid) {
          // Set success message
          $this->message = __('Successfull registration. Ve have sent a verification email. Please check it.', 'startertheme');

          // Set user info
          $userinfo = [
            'ID' => $uid,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'display_name' => $fullname
          ];
          wp_update_user($userinfo);

          // Send an email verification email
          $this->sendVerificationEmail($uid);

          // Redirect
          $redirect = URL::create('login', ['message' => $this->message]);
          return $this->redirect($redirect);
        } else {
          $this->errors['global'] = __('Registration error. Please try again later.', 'startertheme');
        }
      }
    }
  }

  /**
   * Create a registration form
   * @shortcode [login_form]
   */
  public function loginForm()
  {
    // Check if user is already logged in
    $redirect = URL::create('dashboard');
    if (current_user_can('administrator')) {
      return '<div class="alert alert-success mb-3" role="alert">You are already logged in. <a href="' . $redirect . '" class="text-primary">Click here to open the dashboard.</a><div>';
    } elseif (is_user_logged_in()) {
      return $this->redirect($redirect);
    }

    // Set response
    $response = $this->getMessages();

    // Nonce for security (see explanation below)
    global $wp;
    $nonce = wp_create_nonce('startertheme_login_nonce');
    $response .= '
      <form method="post" class="login-form" action="' . htmlspecialchars(\home_url($wp->request)) . '">
        <input type="hidden" name="verify" value="'. $nonce .'">
        ' . Chaptcha::create() . '
        <div class="mb-3">
          <label for="email">' . __('Email', 'startertheme') . '</label>
          <input type="email" class="form-control ' . ($this->errors && isset($this->errors['email']) ? 'is-invalid' : '') . '" id="email" name="email" value="' . (isset($_POST['email']) ? \sanitize_email($_POST['email']) : '') . '" required>
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['email']) ? $this->errors['email'] : '') . '</div>
        </div>
        <div class="mb-3">
          <label for="password">' . __('Password', 'startertheme') . '</label>
          <input type="password" class="form-control ' . ($this->errors && isset($this->errors['password']) ? 'is-invalid' : '') . '" id="password" name="password" required>
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['password']) ? $this->errors['password'] : '') . '</div>
        </div>
        <div class="mb-3">
          <input type="submit" class="btn btn-first w-100" value="' . __('Login', 'startertheme') . '">
        </div>
      </form>
    ';

    return $response;
  }

  /**
   * Validate the data and log the user in
   * @todo - Add this part
   */
  public function loginHandler()
  {
    // Check for valid form submission and nonce verification
    if (isset($_POST['verify']) && wp_verify_nonce($_POST['verify'], 'startertheme_login_nonce')) {
      // Captcha verification
      if (!Chaptcha::verify()) {
        $this->errors['global'] = __('Google reChaptcha verification failed.', 'startertheme');
        return;
      }

      // Sanitize user data
      $email = \sanitize_email($_POST['email']);
      $password = \sanitize_text_field($_POST['password']);

      // Get the user by email the address
      $user = \get_user_by('email', $email);
      if (!$user) {
        $this->errors['global'] = __('Invalid username or password.', 'startertheme');
        return;
      }

      // Check if the email address verified
      if (!\get_user_meta($user->ID, 'email_verified', true)) {
        // $this->sendVerificationEmail($user->ID);
        $this->errors['global'] = __('You need to verify your email address first. The verification link has already sent to your email address.', 'startertheme');

        return;
      }

      // Authenticate user (using wp_signon for proper checks)
      $authenticated = \wp_signon([
        'user_login' => $user->user_login,
        'user_password' => $password,
        'remember' => true // isset($_POST['remember_me'])
      ]);

      if (\is_wp_error($authenticated)) {
        $this->errors['global'] = in_array($authenticated->get_error_code(), ['invalid_username', 'incorrect_password']) ?
          __('Invalid username or password.', 'startertheme') : $authenticated->get_error_code().__('An error occurred during login.', 'startertheme');

        return;
      }

      // Login and redirect the user
      $this->login($authenticated->ID, URL::create('dashboard'));
      exit;
    }
  }

  /**
   * Send verification email to the new registered users
   * @param int $uid
   */
  public function lostPasswordForm()
  {
    global $wp;

    // Set response
    $response = $this->getMessages();

    // Create lost password form
    $nonce = wp_create_nonce('startertheme_lost_password_nonce');
    $response .= '
      <form method="post" class="login-form" action="' . htmlspecialchars(\home_url($wp->request)) . '">
        ' . Chaptcha::create() . '
        <input type="hidden" name="verify" value="'. $nonce .'">
        <div class="mb-3">
          <label for="email">' . __('Email', 'startertheme') . '</label>
          <input type="email" class="form-control ' . ($this->errors && isset($this->errors['email']) ? 'is-invalid' : '') . '" id="email" name="email" value="' . (isset($_POST['email']) ? \sanitize_email($_POST['email']) : '') . '" required>
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['email']) ? $this->errors['email'] : '') . '</div>
        </div>
        <div class="mb-3">
          <input type="submit" class="btn btn-first w-100" value="' . __('Reset password', 'startertheme') . '">
        </div>
      </form>
    ';

    return $response;
  }

  /**
   * Send verification email to the new registered users
   * @param int $uid
   */
  public function lostPasswordHandler()
  {
    // Check for valid form submission and nonce verification
    if (isset($_POST['verify']) && wp_verify_nonce($_POST['verify'], 'startertheme_lost_password_nonce')) {
      // Captcha verification
      if (!Chaptcha::verify()) {
        $this->errors['global'] = __('Google reChaptcha verification failed.', 'startertheme');
        return;
      }

      // Sanitize user data
      $email = \sanitize_email($_POST['email']);

      // Get the user by email the address
      $user = \get_user_by('email', $email);
      if ($user) {
          
        // Generate a reset token
        $verifyToken = $this->createToken($user->user_email, 1, 'password_reset_verification_token');

        // Create verification link
        $resetPasswordUrl = URL::create('password-reset', ['reset_token' => $verifyToken]);

        // Send a verification email to this user
      // Send a verification email to this user
      $label = 'Password reset link';
      $content = '
        <p>Hi,</p>
        <p>
          You have requested a password reseting. You have 24 hours to do that. If you did not sent a request, you do not have any job.<br />
          <a href="'.$resetPasswordUrl.'">Click here to reset your password</a>
        </p>
        <p>
          Kind Regards, <br />
          <a href="'.get_site_url().'">The startertheme tem</a>
        </p>
      ';
        $headers = [
          'Content-Type: text/html; charset=UTF-8',
          'Bcc: boldizar.santo@gmail.com'
        ];

        // Send the verification email out to the user
        \wp_mail($user->user_email, $label, $content, $headers);
      }

      $this->message = __('We will send you a password reset email.', 'startertheme');
    }
  }

  /**
   * Send verification email to the new registered users
   * @param int $uid
   */
  public function resetPasswordForm()
  {
    global $wp;

    // Chheck if there is a reset token. Otherwize, redirect the useo the lost password form
    $resetToken = array_key_exists('reset_token', $_REQUEST) ? $_REQUEST['reset_token'] : false;
    if (!$resetToken) {
      $this->errors['global'] = __('Invalid password reset URL. Please ask for a new one.', 'startertheme') . ' <a href="' . URL::create('lost-password') . '">' . __('Click here', 'startertheme') . '</a>.';
    }

    // Set response
    $response = $this->getMessages();

    // Create lost password form
    $nonce = wp_create_nonce('startertheme_password_reset_nonce');
    $response .= '
      <form method="post" class="reset-password-form" action="' . htmlspecialchars(\home_url($wp->request)) . '">
        ' . Chaptcha::create() . '
        <input type="hidden" name="verify" value="'. $nonce .'">
        <input type="hidden" name="reset_token" value="'. $resetToken .'">
        <div class="mb-3">
          <label for="password">' . __('Password', 'startertheme') . '</label>
          <input type="password" class="form-control ' . ($this->errors && isset($this->errors['password']) ? 'is-invalid' : '') . '" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="password2">' . __('Password again', 'startertheme') . '</label>
          <input type="password" class="form-control ' . ($this->errors && isset($this->errors['password']) ? 'is-invalid' : '') . '" id="password2" name="password2" required>
          <div class="invalid-feedback">' . ($this->errors && isset($this->errors['password']) ? $this->errors['password'] : '') . '</div>
        </div>
        <div class="mb-3">
          <input type="submit" class="btn btn-first w-100" value="' . __('Update password', 'startertheme') . '">
        </div>
      </form>
    ';

    return $response;
  }

  /**
   * Send verification email to the new registered users
   * @param int $uid
   */
  public function resetPasswordHandler()
  {
    // Check for valid form submission and nonce verification
    if (isset($_POST['verify']) && wp_verify_nonce($_POST['verify'], 'startertheme_password_reset_nonce')) {
      // Captcha verification
      if (!Chaptcha::verify()) {
        $this->errors['global'] = __('Google reChaptcha verification failed.', 'startertheme');
        return;
      }

      // Sanitize user data
      $password = \sanitize_text_field($_POST['password']);
      $password2 = \sanitize_text_field($_POST['password2']);

      // Validate password
      if (empty($password) || empty($password2)) {
        $this->errors['password'] = __('Password is required.', 'startertheme');
      } elseif (strlen($password) < 8 || strlen($password2) < 8) {
        $this->errors['password'] = __('Password mut be at leaset 8 characthers long.', 'startertheme');
      } elseif ($password !== $password2) {
        $this->errors['password'] = __('Passwords are not the same.', 'startertheme');
      }

      // Chheck if there is a reset token. Otherwize, redirect the useo the lost password form
      $resetToken = array_key_exists('reset_token', $_REQUEST) ? $_REQUEST['reset_token'] : false;
      if (!$resetToken) {
        $this->errors['global'] = __('Invalid password reset URL. Please ask for a new one.', 'startertheme') . ' <a href="' . URL::create('lost-password') . '">' . __('Click here', 'startertheme') . '</a>.';
      }

      // If no errors, create the user
      if (empty($this->errors)) {
        // Verify the reset token and get the user
        $validUid = $this->verifyToken($resetToken, 'password_reset_verification_token');
        if ($validUid) {
          // Reset password
          \wp_set_password($password, $validUid);
          $this->logout();

          // Redirect with message
          $this->message = __('Password successfully updated. You can login now.', 'startertheme');
          $redirect = URL::create('login', ['message' => $this->message]);
          return $this->redirect($redirect);
        } else {
          $this->errors['global'] = __('Invalid password reset URL. Please ask for a new one.', 'startertheme') . ' <a href="' . URL::create('lost-password') . '">' . __('Click here', 'startertheme') . '</a>.';
        }
      }
    }
  }

  /**
   * Send verification email to the new registered users
   * @param int $uid
   */
  public function sendVerificationEmail($uid)
  {
    $user = \get_user_by('id', $uid);
    if ($user) {
      // Generate a reset token
      $verifyToken = $this->createToken($user->user_email, 1, 'email_verification_token');

      // Create verification link
      $verificationURL = URL::create('login', ['verify_token' => $verifyToken]);

      // Send a verification email to this user
      $label = 'Verify your email!';
      $content = '
        <p>Hi,</p>
        <p>
          Please verify your email address, <a href="' . $verificationURL . '">click here</a>.
        </p>
        <p>
          Kind Regards, <br />
          <a href="'.get_site_url().'">The startertheme team</a>
        </p>
      ';
      $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Bcc: boldizar.santo@gmail.com'
      ];

      // Send the verification email out to the user
      $sent = \wp_mail($user->user_email, $label, $content, $headers);

      return $sent ? true : false;
    }
  }

  /**
   * Verify email
   */
  public function verifyEmail()
  {
    if (array_key_exists('verify_token', $_GET) && $verifyToken = $_GET['verify_token']) {

      // Fetch the verification data
      $validUid = $this->verifyToken($verifyToken, 'email_verification_token');
      if ($validUid) {
        \update_user_meta($validUid, 'email_verified', true);
        $this->message = __('Successfully verified. Now, you can login.', 'startertheme');
      } else {
        $this->errors['global'] = __('Unuccessfull verification attempt.', 'startertheme');
      }
    }
  }

  /**
   * Return messages
   */
  public function getMessages()
  {
    // Set response
    $response = '';

    // Display message
    if ($this->message) {
      $response .= '<div class="alert alert-success mb-3" role="alert">' . $this->message . '</div>';
    }

    // Display message
    if ($this->errors && isset($this->errors['global'])) {
      $response .= '<div class="alert alert-danger mb-3" role="alert">' . $this->errors['global'] . '</div>';
    }

    return $response;
  }
}
