<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTlayout {

    static function getNoRecordFound() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/no-record-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('No record found ...!', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getPermissionNotGranted() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('You have no permission to access this page ...!', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getNotStaffMember() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('User are not allowed to access this page.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getStaffMemberDisable() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('Your account has been disabled, Please contact to the administrator.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getSystemOffline() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/offline-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Offline', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . jssupportticket::$_config['offline_message'] . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }
    
    static function getRegistrationDisabled() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/ban.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('Registration has been disabled by admin, Please contact to the system administrator.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }
     static function getUserGuest($redirect_url = '') {
        $loginval = JSSTincluder::getJSModel('configuration')->getConfigValue('set_login_link');
        $loginlink = JSSTincluder::getJSModel('configuration')->getConfigValue('login_link');
        $html = '
                <div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-login-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('You are not logged In', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('To access the page, Please login', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-user-login-btn-wrp">';
	                        if($loginval == 2 && $loginlink != ""){
	                            $html .= '<a class="js-ticket-login-btn" href="'.esc_url($loginlink).'" title="Login">' . __('Login', 'js-support-ticket') . '</a>';
	                        }else{
	                            $html .= '<a class="js-ticket-login-btn" href="'.esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'login', 'js_redirecturl'=>$redirect_url))).'" title="Login">' . __('Login', 'js-support-ticket') . '</a>';
	                        }
	                        $is_enable = get_option('users_can_register');// check to make sure user registration is enabled
                            if ($is_enable) { /* only show the registration form if allowed */
	                        	$html .= '<a class="js-ticket-register-btn" href="'.esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'userregister', 'js_redirecturl'=>$redirect_url))).'" title="Login">' . __('Register', 'js-support-ticket') . '</a>';
							}
                    $html .= '</span> </div>
					</div>
				</div>
        ';
        echo $html;
    }

}

?>
