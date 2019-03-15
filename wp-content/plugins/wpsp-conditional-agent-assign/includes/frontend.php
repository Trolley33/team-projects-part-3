<?php
final class WPSPConditionalAgentAssignFrontend {

    public function wpsp_after_create_ticket($ticket_id){

        global $current_user, $wpspcaa, $wpdb;

        $ticket=$wpspcaa->functions->get_ticket_by_id($ticket_id);

        $ass_agent=array();
        $wpsp_caa_conditions = get_option( 'wpsp_caa_rule' );
        
        if(!empty($wpsp_caa_conditions)){
          
          foreach ($wpsp_caa_conditions as $key => $rule) {

            if($wpspcaa->functions->check_condition($rule,$ticket)){
              $ass_agent = array_merge($ass_agent, $rule['agents'] );
            }
          }
        }

        if($ass_agent){
            $values = array(
                'assigned_to'    => implode(',', array_unique($ass_agent) )
            );

            $wpdb->update( $wpdb->prefix.'wpsp_ticket', $values, array('id'=>$ticket_id) );
        }
    }
}
?>
