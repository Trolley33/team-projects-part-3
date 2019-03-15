<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$wpsp_caa_action = isset( $_REQUEST['wpsp_caa_action'] ) ? sanitize_text_field( $_REQUEST['wpsp_caa_action'] ) : 'view_settings';

switch ( $wpsp_caa_action ) {

  case 'view_settings':
		include WPSP_CAA_DIR . 'includes/admin/view_settings.php';
		break;

  case 'add_caa_rule':
  	include WPSP_CAA_DIR . 'includes/admin/add_caa_rule_html.php';
  	break;

  case 'edit_setting':
  		include WPSP_CAA_DIR . 'includes/admin/edit_setting.php';
  		break;

}
