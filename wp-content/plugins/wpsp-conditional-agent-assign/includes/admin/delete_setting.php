<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rule_id	=	isset($_REQUEST['rule_id']) && is_numeric($_REQUEST['rule_id']) ? intval($_REQUEST['rule_id']) : 'NA';
$nonce	= isset($_REQUEST['nonce'])	? sanitize_text_field($_REQUEST['nonce']) : '';

if ( !is_numeric($rule_id) || !$nonce || !wp_verify_nonce($nonce,$rule_id) ) {
	die(__('Cheating huh?', 'wpsp-caa'));
}

$wpsp_caa_rule = get_option('wpsp_caa_rule') ? get_option('wpsp_caa_rule') : array();

if (isset($wpsp_caa_rule[$rule_id])) {

	unset($wpsp_caa_rule[$rule_id]);
	update_option( 'wpsp_caa_rule', $wpsp_caa_rule );

}

wp_redirect( 'admin.php?page=wp-support-plus&setting=addons&section=caa-settings' );
exit();
