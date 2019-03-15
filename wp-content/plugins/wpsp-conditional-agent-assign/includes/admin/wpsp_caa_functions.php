<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'WPSP_CAA_Functions' ) ) :

    /**
     * Functions class for WPSP.
     * @class WPSP_Export_Functions
     */
    class WPSP_CAA_Functions {

			public function get_option_value_label($field_key,$value,$conditional_fields){

				global $wpdb, $wpsupportplus, $current_user;

				if ( $conditional_fields[$field_key]['type']=='text' ) {

					return $value;

				} else if ( is_numeric($field_key) ) {

					return apply_filters( 'wpsp_en_get_option_value_label', $value, $field_key, $conditional_fields );

				} else {

					if ( $field_key=='status_id' ) {
						$label = $wpdb->get_var("select name from {$wpdb->prefix}wpsp_custom_status WHERE id=".$value);
						return stripcslashes($label);
					}

					if ($field_key=='cat_id') {
						$label = $wpdb->get_var("select name from {$wpdb->prefix}wpsp_catagories WHERE id=".$value);
						return stripcslashes($label);
					}

					if ($field_key=='priority_id') {
						$label = $wpdb->get_var("select name from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$value);
						return stripcslashes($label);
					}

				}
			}

			public function get_field_options(){

				global $wpdb, $wpsupportplus, $current_user;

				$field_key =  isset($_REQUEST['field_key']) ? sanitize_text_field($_REQUEST['field_key']) : '';

				if ( !$field_key || !$current_user->has_cap('manage_options')) {
					die(__('Cheating huh?', 'wp-support-plus-responsive-ticket-system'));
				}

				?>
				<option value=""><?php _e('Select Option', 'wpsp-en');?></option>
				<?php

				if ($field_key=='status_id') {
					$results = $wpdb->get_results("select * from {$wpdb->prefix}wpsp_custom_status ORDER BY load_order");
					foreach ( $results as $status ) {
						?>
						<option value="<?php echo $status->id?>"><?php echo stripcslashes($status->name)?></option>
						<?php
					}
				}
				if ($field_key=='cat_id') {
					$results = $wpdb->get_results("select * from {$wpdb->prefix}wpsp_catagories ORDER BY load_order");
					foreach ( $results as $category ) {
						?>
						<option value="<?php echo $category->id?>"><?php echo stripcslashes($category->name)?></option>
						<?php
					}
				}
				if ($field_key=='priority_id') {
					$results = $wpdb->get_results("select * from {$wpdb->prefix}wpsp_custom_priority ORDER BY load_order");
					foreach ( $results as $priority ) {
						?>
						<option value="<?php echo $priority->id?>"><?php echo stripcslashes($priority->name)?></option>
						<?php
					}
				}
				if (is_numeric($field_key)) {

					$default_option_types = array(2,3,4);
					$custom_field = $wpdb->get_row("select * from {$wpdb->prefix}wpsp_custom_fields WHERE id=".$field_key);
					if ( in_array($custom_field->field_type, $default_option_types) ) {
						$options = unserialize($custom_field->field_options);
						foreach( $options as $key => $val ){
							?>
							<option value="<?php echo trim(htmlspecialchars( stripcslashes($key), ENT_QUOTES ))?>"><?php echo trim(stripcslashes($val))?></option>
							<?php
						}
					} else {
						do_action( 'wpsp_en_get_field_options', $field_key, $custom_field );
					}
				}
				die();

			}

			public function get_assigned_agents_to_rule($caa_rule){
				$users=array();
				if( isset( $caa_rule['agents'] ) ){
					$agent_ids=$caa_rule['agents'];
					foreach ($agent_ids as $key => $value) {
						$user_obj = get_user_by('id', $value);
						$users[]=$user_obj;
					}
				}

				return $users;
			}

			public function get_ticket_by_id($ticket_id){

				global $wpdb;
				$ticket = $wpdb->get_row( "select * from {$wpdb->prefix}wpsp_ticket where id=".$ticket_id );

				return $ticket;
			}

			public function check_condition( $rule, $ticket ){

	      global $wpdb, $wpsupportplus, $current_user,$wpspcaa;
	      $conditional_fields = $wpsupportplus->functions->get_conditional_fields();
	      $conditions =  isset($rule['condition']) && is_array($rule['condition']) ? $rule['condition'] : array();
	      if ($conditions) {

	        $diif_fields = array();

	        foreach ($conditions as $field_key => $options) {

	          $same_fields = array();
	          foreach ($options as $option) {

	            $same_flag = false;

	            if ( !is_numeric($field_key) && $conditional_fields[$field_key]['type']=='text') {
	              if(preg_match('/'.$option.'/i', $ticket->$field_key)){
	                $same_flag = true;
	              }
	            }

	            if ( is_numeric($field_key) && $conditional_fields[$field_key]['type']=='text') {
	              $key = 'cust'.$field_key;
	              if(preg_match('/'.$option.'/i', $ticket->$key)){
	                $same_flag = true;
	              }
	            }

	            if ( !is_numeric($field_key) && $conditional_fields[$field_key]['type']=='drop-down') {

	              $field_options = explode( '|||', $ticket->$field_key);
	              foreach ($field_options as $value) {
	                if( $value == $option){
	                  $same_flag = true;
	                  break;
	                }
	              }

	            }

	            if ( is_numeric($field_key) && $conditional_fields[$field_key]['type']=='drop-down') {

	              $key = 'cust'.$field_key;
	              $field_options = explode( '|||', $ticket->$key);
	              foreach ($field_options as $value) {
	                if( $value == $option){
	                  $same_flag = true;
	                  break;
	                }
	              }

	            }

	            $same_fields[] = $same_flag;

	          }

	          $diff_flag = false;
	          if( $rule['same_condition_relation']=='AND' && !in_array( false , $same_fields ) ){
	            $diff_flag = true;
	          }
	          if( $rule['same_condition_relation']=='OR' && in_array( true , $same_fields ) ){
	            $diff_flag = true;
	          }
	          $diif_fields[] = $diff_flag;

	        }

	        $outer_flag = false;
	        if( $rule['diff_condition_relation']=='AND' && !in_array( false , $diif_fields ) ){
	          $outer_flag = true;
	        }
	        if( $rule['diff_condition_relation']=='OR' && in_array( true , $diif_fields ) ){
	          $outer_flag = true;
	        }

	        return $outer_flag;

	      }

	    }

    }
endif;
