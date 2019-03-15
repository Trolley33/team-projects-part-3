<?php
final class WPSPConditionalAgentAssignBackend {

    function wpsp_en_actions(){

      $wpsp_caa_action = isset( $_REQUEST['wpsp_caa_action'] ) ? sanitize_text_field( $_REQUEST['wpsp_caa_action'] ) : 'view_settings';

      switch ( $wpsp_caa_action ) {

        case 'insert_caa_rule':
        	include WPSP_CAA_DIR . 'includes/admin/insert_caa_rule.php';
        	break;

        case 'update_setting':
        		include WPSP_CAA_DIR . 'includes/admin/update_setting.php';
        		break;

        case 'delete_setting':
        		include WPSP_CAA_DIR . 'includes/admin/delete_setting.php';
        		break;
      }
      
    }

    function loadScripts(){
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script('wpsp_caa_admin', WPSP_CAA_URL . 'asset/js/admin.js?version='.WPSP_CAA_VERSION);
        wp_enqueue_style('wpsp_caa_admin', WPSP_CAA_URL . 'asset/css/admin.css?version='.WPSP_CAA_VERSION);

        $localize_script_data=array(
            'ajax_url'         => admin_url( 'admin-ajax.php' ),
            'condition_name'   => __('Please Enter Condition Name','wpsp-caa'),
            'agent_required'   => __('Please select at least one agent!','wpsp-caa'),
            'active_rule' => __('Add at least one condition!','wpsp-caa'),
            'agent_duplicate'  => __('Agent already added!','wpsp-caa')
        );
        wp_localize_script( 'wpsp_caa_admin', 'wpsp_caa_data', $localize_script_data );

    }

    public function wpsp_addon_submenus_sections($addon_sections){

        $addon_sections['caa-settings'] = array(
                'label' => __('Conditional Assign Agents','wpsp-caa'),
                'file'  => WPSP_CAA_DIR . 'includes/admin/settings-tabs/wpsp_contional_assign_setting.php'
            );

        return $addon_sections;
    }
    
    public function wpsp_addon_count($addon_count){
      
        return ++$addon_count;
      
    }
    
    public function license_setting(){
        
        include WPSP_CAA_DIR . 'includes/admin/license_setting.php';
        
    }
    
    public function license_setting_update(){
        
        $setting = sanitize_text_field( $_REQUEST['update_setting'] );
        
        if ( $setting === 'settings_addon_licenses' ){
            include WPSP_CAA_DIR . 'includes/admin/license_update.php';
        }
        
    }
        
}
?>
