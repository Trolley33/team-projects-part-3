<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpsp_caa_rule = get_option('wpsp_caa_rule') ? get_option('wpsp_caa_rule') : array();
$rule         = isset($_REQUEST['wpsp_caa_rule']) ? $_REQUEST['wpsp_caa_rule'] : array();

// exit if no notification found
if( !$rule ) {
  wp_redirect( 'admin.php?page=wp-support-plus&setting=addons&section=caa-settings' );
  exit();
}

// insert new index for email notification
$wpsp_caa_rule[] = $rule;

// get last index of the array
end($wpsp_caa_rule);
$key = key($wpsp_caa_rule);
reset($wpsp_caa_rule);

update_option( 'wpsp_caa_rule', $wpsp_caa_rule );

// continue edit of the notification
wp_redirect( 'admin.php?page=wp-support-plus&setting=addons&section=caa-settings&wpsp_caa_action=edit_setting&rule_id='.$key );
exit();
