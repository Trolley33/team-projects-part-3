<?php
if (!defined('ABSPATH'))
    die('Restricted Access');


// wrong username password handling
add_action( 'wp_login_failed', 'jssupportticket_login_failed', 10, 2 );
function jssupportticket_login_failed( $username ){
    $referrer = wp_get_referer();
    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer, 'wp-admin') ){
        if (isset($_POST['wp-submit'])){
            JSSTmessage::setMessage(__('Username / password is incorrect', 'js-support-ticket'), 'error');
            wp_redirect(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket','jstlay'=>'login','jsstpageid'=>jssupportticket::getPageid())));
            exit;
        }else{
            return;
        }
    }
}

// Updates authentication to return an error when one field or both are blank
add_filter( 'authenticate', 'jsst_authenticate_username_password', 30, 3);

function jsst_authenticate_username_password( $user, $username, $password ){
    if ( is_a($user, 'WP_User') ) { 
        return $user; 
    }
    if (isset($_POST['wp-submit']) && (empty($_POST['pwd']) || empty($_POST['log']))){
        return false;
    }
    return $user;
}
// ------------------- jsst registrationFrom request handler--------
// register a new user
function jsst_add_new_member() {
    if (isset($_POST["jsst_user_login"]) && wp_verify_nonce($_POST['jsst_support_register_nonce'], 'jsst-support-register-nonce')) {
        $user_login = $_POST["jsst_user_login"];
        $user_email = $_POST["jsst_user_email"];
        $user_first = $_POST["jsst_user_first"];
        $user_last = $_POST["jsst_user_last"];
        $user_pass = $_POST["jsst_user_pass"];
        $pass_confirm = $_POST["jsst_user_pass_confirm"];

        // this is required for username checks
        // require_once(ABSPATH . WPINC . '/registration.php');

        if (username_exists($user_login)) {
            // Username already registered
            jsst_errors()->add('username_unavailable', __('Username already taken', 'js-support-ticket'));
        }
        if (!validate_username($user_login)) {
            // invalid username
            jsst_errors()->add('username_invalid', __('Invalid username', 'js-support-ticket'));
        }
        if ($user_login == '') {
            // empty username
            jsst_errors()->add('username_empty', __('Please enter a username', 'js-support-ticket'));
        }
        if (!is_email($user_email)) {
            //invalid email
            jsst_errors()->add('email_invalid', __('Invalid email', 'js-support-ticket'));
        }
        if (email_exists($user_email)) {
            //Email address already registered
            jsst_errors()->add('email_used', __('Email already registered', 'js-support-ticket'));
        }
        if ($user_pass == '') {
            // passwords do not match
            jsst_errors()->add('password_empty', __('Please enter a password', 'js-support-ticket'));
        }
        if ($user_pass != $pass_confirm) {
            // passwords do not match
            jsst_errors()->add('password_mismatch', __('Passwords do not match', 'js-support-ticket'));
        }
        if (jssupportticket::$_config['captcha_on_registration'] == 1) {
            if (jssupportticket::$_config['captcha_selection'] == 1) { // Google recaptcha
                $gresponse = $_POST['g-recaptcha-response'];
                $resp = googleRecaptchaHTTPPost(jssupportticket::$_config['recaptcha_privatekey'],$gresponse);
                if (!$resp) {
                    jsst_errors()->add('invalid_captcha', __('Invalid captcha', 'js-support-ticket'));
                }
            } else { // own captcha
                $captcha = new JSSTcaptcha;
                $result = $captcha->checkCaptchaUserForm();
                if ($result != 1) {
                    jsst_errors()->add('invalid_captcha', __('Invalid captcha', 'js-support-ticket'));
                }
            }
        }

        
        $errors = jsst_errors()->get_error_messages();

        // only create the user in if there are no errors
        if (empty($errors)) {

            $new_user_id = wp_insert_user(array(
                'user_login' => $user_login,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'first_name' => $user_first,
                'last_name' => $user_last,
                'user_registered' => date_i18n('Y-m-d H:i:s'),
                'role' => jssupportticket::$_config['wp_default_role']
                )
            );
            if ($new_user_id) {
                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);
                // log the new user in
                wp_set_current_user($new_user_id, $user_login);
                wp_set_auth_cookie($new_user_id);
                do_action('wp_login', $user_login);
                $url = jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel','jsstpageid'=>jssupportticket::getPageid()));
                // send the newly created user to the home page after logging them in
                wp_redirect($url);
                exit;
            }
        }
    }
}

add_action('init', 'jsst_add_new_member');

// used for tracking error messages
function jsst_errors() {
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function jsst_show_error_messages() {
    if ($codes = jsst_errors()->get_error_codes()) {
        echo '<div class="jsst_errors">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = jsst_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}


?>
