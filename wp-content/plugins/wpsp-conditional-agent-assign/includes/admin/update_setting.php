<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpsp_caa_rule = get_option('wpsp_caa_rule') ? get_option('wpsp_caa_rule') : array();
$rule_id       = isset( $_POST['rule_id'] ) ? intval($_POST['rule_id']) : 'NA';
$css_rules     = isset($_POST['wpsp_caa_rule']) ? $_POST['wpsp_caa_rule'] : array();

// exit if no notification found
if( !is_numeric($rule_id) || !$css_rules ) {
  echo (admin_url().'admin.php?page=wp-support-plus&setting=addons&section=caa-settings');
	die();
}
// insert new index for email notification
$wpsp_caa_rule[$rule_id] = $css_rules;
$a = update_option( 'wpsp_caa_rule', $wpsp_caa_rule );

echo (admin_url().'admin.php?page=wp-support-plus&setting=addons&section=caa-settings');
die();

