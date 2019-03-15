<?php
/**
 * Plugin Name: WP Support Plus Conditional Agent Assign
 * Plugin URI: http://www.wpsupportplus.com
 * Description: Conditional Agent Assign Addon!
 * Version: 2.0.6
 * Author: Pradeep Makone
 * Author URI: https://www.wpsupportplus.com
 * Text Domain: wpsp-caa
 * Domain Path: /lang
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( class_exists('WP_Support_Plus') ) {
    $GLOBALS['wpspcaa'] = new WPSP_CONDITIONAL_AGENT_ASSIGN();
}

final class WPSP_CONDITIONAL_AGENT_ASSIGN {

    /*
     * Constructor
     */
    public function __construct() {
        
				$this->define_constants();
        add_action( 'init', array($this,'load_textdomain') );
        
        $this->include_files();
				
				//plugin updator
        add_action('admin_init',array($this,'plugin_updator'));
				
    }

    function define_constants(){
        
				define( 'WPSP_CAA_PLUGIN_FILE', __FILE__ );
				define( 'WPSP_CAA_URL', plugin_dir_url( __FILE__ ) );
        define( 'WPSP_CAA_DIR', plugin_dir_path( __FILE__ ) );
				define( 'WPSP_CAA_VERSION', '2.0.6' );
				define( 'WPSP_CAA_STORE_ID', '7586' );
				
    }

		function load_textdomain(){
			 $locale = apply_filters( 'plugin_locale', get_locale(), 'wpsp-caa' );
			 load_textdomain( 'wpsp-caa', WP_LANG_DIR . '/wpsp/wpsp-caa-' . $locale . '.mo' );
			 load_plugin_textdomain( 'wpsp-caa', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	 }

    function include_files(){
			
				include_once( WPSP_CAA_DIR.'includes/admin/installation.php' );
        include_once( WPSP_CAA_DIR.'includes/admin/wpsp_caa_functions.php' );
        include_once( WPSP_CAA_DIR.'includes/admin/class-wpsp-caa-ajax.php' );
				include( WPSP_CAA_DIR.'includes/admin/admin.php' );
				include( WPSP_CAA_DIR.'includes/frontend.php' );
				
				$frontend = new WPSPConditionalAgentAssignFrontend();
				$backend  = new WPSPConditionalAgentAssignBackend();

        $this->set_class_objects();

        if (is_admin()) {

						add_action( 'admin_init', array($backend, 'wpsp_en_actions') );
            add_action( 'admin_enqueue_scripts', array( $backend, 'loadScripts') );
            add_filter( 'wpsp_addon_submenus_sections', array($backend, 'wpsp_addon_submenus_sections'), 10 , 1 );
						
						// License related actions
            add_filter( 'wpsp_addon_count', array( $backend, 'wpsp_addon_count') );
						add_action( 'wpsp_addon_license_setting', array( $backend, 'license_setting') );
						add_action( 'wpsp_setting_update', array( $backend, 'license_setting_update') );
						
        } else {
					
				}
				
				add_filter( 'wpsp_after_create_ticket', array($frontend, 'wpsp_after_create_ticket') , 10 , 1 );
    }

    private function set_class_objects(){

        $this->functions=new WPSP_CAA_Functions();
        $this->ajax = new WPSP_CAA_Ajax();
    }
		
		function plugin_updator(){
				
				$license_key = get_option( 'wpsp_license_key_condagentassign' );
				if ($license_key) {
					$edd_updater = new EDD_SL_Plugin_Updater( WPSP_STORE_URL, __FILE__, array(
									'version' => WPSP_CAA_VERSION,
									'license' => $license_key,
									'item_id' => WPSP_CAA_STORE_ID,
									'author'  => 'Pradeep Makone',
									'url'     => site_url()
					) );
				}
				
    }
		
}
?>
