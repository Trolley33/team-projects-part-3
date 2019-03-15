<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSP_CAA_Install' ) ) :
	/**
	 * WPSP installation and updating class
	 */
	class WPSP_CAA_Install {

			/**
			 * Constructor
			 */
			public function __construct() {

					register_activation_hook( WPSP_CAA_PLUGIN_FILE, array($this,'install') );
					register_deactivation_hook( WPSP_CAA_PLUGIN_FILE, array($this,'deactivate') );
					$this->check_version();
			}

			/**
			 * Check version of WPSP
			 */
			private function check_version(){

					$installed_version = get_option( 'wpsp_caa_version' );
					if( $installed_version != WPSP_CAA_VERSION ){
							$this->install();
					}

					// last version where upgrade check done
					$upgrade_version = get_option( 'wpsp_caa_upgrade_version' );
					if( $upgrade_version != WPSP_CAA_VERSION ){
							$this->upgrade();
							update_option( 'wpsp_caa_upgrade_version', WPSP_CAA_VERSION );
					}

			}

			/**
			 * Install WPSP
			 */
			function install(){

					$this->create_tables();
					update_option( 'wpsp_caa_version', WPSP_CAA_VERSION );
			}

			/**
			 * Deactivate WPSP actions
			 */
			public function deactivate() {

			}

			/**
			 * Create tables for WPSP
			 */
			function create_tables(){

			}


			/**
			 * Upgrade process begin
			 */
			function upgrade(){
				
					$upgrade_version = get_option( 'wpsp_caa_upgrade_version' ) ? get_option( 'wpsp_caa_upgrade_version' ) : 0;

					//Version 2.0.0
					if( $upgrade_version < '2.0.0' ){
							
							$wpsp_caa_conditions = get_option( 'wpsp_caa_conditions' );
							
							if($wpsp_caa_conditions){
									$new_rules=array();
									foreach($wpsp_caa_conditions as $key => $condition ){
										$rule=array();
										$rule['title']=$condition['label'];
										$rule['agents']=$condition['agents'];
										$rules=$condition['rules'];
										foreach ($rules as $key => $value) {
											
											if($key=='create_ticket_category' && $value['status']){
												$rule['condition']['cat_id']=$value['options'];
											}
											else if($key=='create_ticket_priority' && $value['status']){
												global $wpdb;
												$priorities=$value['options'];
												$pids=array();
												foreach ($priorities as $key => $value) {
													$pid=$wpdb->get_var("select id from {$wpdb->prefix}wpsp_custom_priority where name='".$value."'");
													$pids[]=$pid;
												}
												$rule['condition']['priority_id']=$pids;
											}
											else{
												if($value['status']){
													$namecust=explode("cust",$key);
													$custid=$namecust[1];
													$rule['condition'][$custid]=$value['options'];
												}
												
											}
											
										}
										$rule['same_condition_relation']="OR";
										$rule['diff_condition_relation']="AND";
										$new_rules[]=$rule;
									}
									update_option('wpsp_caa_rule',$new_rules);
							}
					}
			}
}
endif;

new WPSP_CAA_Install();