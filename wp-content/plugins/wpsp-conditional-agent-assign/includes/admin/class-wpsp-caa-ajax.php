<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSP_CAA_Ajax' ) ) :

    /**
     * Ajax class for WPSP.
     * @class WPSP_Ajax
     */
    class WPSP_CAA_Ajax {

        public function __construct(){
             $ajax_events = array(
							 		'search_agents_for_caa'	=>	false,
								  'update_setting' 				=>	false
             );

             foreach ($ajax_events as $ajax_event => $nopriv) {
                add_action('wp_ajax_' . $ajax_event, array($this, $ajax_event));
                if ($nopriv) {
                    add_action('wp_ajax_nopriv_' . $ajax_event, array($this, $ajax_event));
                }
            }
        }

				public function search_agents_for_caa(){
					include WPSP_CAA_DIR . 'includes/admin/settings-tabs/ajax/search_agents_for_caa.php';
					die();
				}
				
				public function update_setting(){
		      include WPSP_CAA_DIR . 'includes/admin/update_setting.php';
					die();
		    }
					
    }

endif;
