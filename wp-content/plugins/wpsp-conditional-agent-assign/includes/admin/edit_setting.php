<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpsupportplus,$wpspcaa;
$wpsp_caa_rule = get_option('wpsp_caa_rule');
$rule_id       = isset( $_REQUEST['rule_id'] ) ? intval($_REQUEST['rule_id']) : 'NA';

// exit if invalid id
if( !is_numeric($rule_id) ) {
  wp_redirect( 'admin.php?page=wp-support-plus&setting=addons&section=caa-settings' );
  exit();
}

$caa_rule=$wpsp_caa_rule[$rule_id];
?>

<div id="tab_container">

    <form id="wpsp_frm_en_edit" method="post" action="#">
			<input type="hidden" name="action" value="update_setting"/>
      <input type="hidden" name="rule_id" value="<?php echo $rule_id?>">

      <table class="form-table">

          <tr>
            <th scope="row"><h3><?php _e( 'Edit Condition', 'wpsp-caa' );?></h3></th>
            <td></td>
          </tr>

          <tr>
              <th scope="row"><?php _e( 'Title', 'wpsp-caa' );?></th>
              <td>
                <input type="text" name="wpsp_caa_rule[title]" style="width:330px" value="<?php echo $caa_rule['title']; ?>" />
              </td>
          </tr>

					<tr>
							<th><?php _e('Add Agents','wpsp-caa');?></th>
							<td>

									<div class="wpsp-autocomplete-drop-down" id="wpsp-agent" style="float: left;" >
											<span id="wpsp-agent-label"><?php _e('Select Agents','wpsp-caa')?></span>
											<input id="wpsp-agent-id" type="hidden" name="wpsp_agent[agent_id]" />
											<span style="float: right;" class="dashicons dashicons-arrow-down-alt2"></span>
									</div>

									<div class="wpsp-autocomplete-drop-down-panel" style="float: left;">
											<input style="margin: 0;width: 100%;padding: 8px;" onkeyup="wpsp_search_keypress(event,this);" type="text" id="wpsp-autocomplete-search" placeholder="<?php _e('Search Agents...','wpsp-caa')?>" autocomplete="off" />
											<div id="wpsp-autocomplete-search-results"></div>
									</div>

									<button id="wpsp_add_agent" class="page-title-action" style="margin: 15px 15px;" onclick="wpsp_add_user_tbl(event)"><?php _e('Add','wpsp-caa')?></button>
							</td>
					</tr>

					<tr>
							<th><?php _e('Assigned Agents','wpsp-caa');?></th>
							<td>
									<?php
									$comp_users=$wpspcaa->functions->get_assigned_agents_to_rule($caa_rule);
									?>
									<table id="wpsp_selected_agents" style="width:330px">
											<thead>
													<tr>
															<th><?php _e('Name','wpsp-caa');?></th>
															<th style="width: 50px;"></th>
													</tr>
											</thead>
											<tbody>
													<?php
															foreach ($comp_users as $user){

																	echo "<tr><td>" . $user->display_name . "<input type='hidden' name='wpsp_caa_rule[agents][]' value='".$user->ID."'/></td>
																							<td><span class='delete-row'>". __('Remove','wpsp-caa') ."</span></td> </tr>";
															}
													?>
											</tbody>
									</table>
							</td>
					</tr>
					<?php if ( class_exists('WPSP_EN') ) {
					 ?>
          <tr>

						<th scope="row"><?php _e( 'Conditions', 'wpsp-caa' );?></th>

						<td>

							<button type="button" onclick="wpsp_en_show_add_condition();" class="button button-secondery" name=""><?php echo _e('Add New', 'wpsp-caa');?></button>

							<div id="wpsp_en_condition_add_container">
								<select id="wpsp_en_conditional_field" onchange="wpsp_en_conditional_field_set(this);">
									<option value=""><?php _e('Select Field', 'wpsp-caa');?></option>
									<?php $conditional_fields = $wpsupportplus->functions->get_conditional_fields();?>
									<?php foreach ($conditional_fields as $key => $value): ?>
										<option data-type="<?php echo $value['type']?>" value="<?php echo $key?>"><?php echo $value['label']?></option>
									<?php endforeach; ?>
								</select>
								<select id="wpsp_en_conditional_field_option" class="wpsp_en_conditional_values" onchange="wpsp_en_conditional_option_has_option(this);">
									<option value=""><?php _e('Select Option', 'wpsp-caa');?></option>
								</select>
								<input id="wpsp_en_conditional_field_has_word" onkeyup="wpsp_en_conditional_has_word_has_char(this);" class="wpsp_en_conditional_values" type="text" placeholder="<?php _e('Has Word', 'wpsp-caa');?>">
								<button type="button" id="wpsp_en_btn_add_condition" class="button button-primary wpsp_en_conditional_values" onclick="wpsp_caa_set_add_condition();"><?php _e('Add', 'wpsp-caa');?></button>
							</div>

							<div id="wpsp_en_condition_container">
								<?php $conditions =  isset($caa_rule['condition']) && is_array($caa_rule['condition']) ? $caa_rule['condition'] : array();?>
								<?php foreach ($conditions as $key => $fields): ?>
									<?php foreach ($fields as $value): ?>
										<div class="wpsp_autocomplete_choice_item">
											<?php echo $conditional_fields[$key]['label']?> = "<?php echo $wpspcaa->functions->get_option_value_label($key,$value,$conditional_fields)?>" <span onclick="wpsp_autocomplete_choice_item_delete(this)" class="dashicons dashicons-no-alt wpsp_autocomplete_choice_item_delete"></span>
											<input name="wpsp_caa_rule[condition][<?php echo $key?>][]" value="<?php echo $value?>" type="hidden">
										</div>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</div>

						</td>

					</tr>

					<tr>
              <th scope="row"><?php _e( 'Same condition field match relation', 'wpsp-caa' );?></th>
              <td>
                <select name="wpsp_caa_rule[same_condition_relation]">
									<option <?php echo isset($caa_rule['same_condition_relation']) && $caa_rule['same_condition_relation'] == 'OR' ? 'selected="selected"' : ''?> value="OR"><?php _e( 'Any one of them', 'wpsp-caa' );?></option>
									<option <?php echo isset($caa_rule['same_condition_relation']) && $caa_rule['same_condition_relation'] == 'AND' ? 'selected="selected"' : ''?> value="AND"><?php _e( 'All of them', 'wpsp-caa' );?></option>
								</select>
              </td>
          </tr>

					<tr>
              <th scope="row"><?php _e( 'Different condition field match relation', 'wpsp-caa' );?></th>
              <td>
                <select name="wpsp_caa_rule[diff_condition_relation]">
									<option <?php echo isset($caa_rule['diff_condition_relation']) && $caa_rule['diff_condition_relation'] == 'AND' ? 'selected="selected"' : ''?> value="AND"><?php _e( 'All of them', 'wpsp-caa' );?></option>
									<option <?php echo isset($caa_rule['diff_condition_relation']) && $caa_rule['diff_condition_relation'] == 'OR' ? 'selected="selected"' : ''?> value="OR"><?php _e( 'Any one of them', 'wpsp-caa' );?></option>
								</select>
              </td>
          </tr>
					<?php }else{

						echo "<tr><td></td><td><b>".__('Please install Email Notification add-on','wpsp-caa')."</b></td></tr>";
					} ?>
					<tr>
						<td colspan="2">
							<input id="submit" style="margin-left:-10px;" onclick="wpsp_caa_rule_form(event);" class="button button-primary" name="submit" value="<?php _e( 'Save Changes', 'wpsp-caa' );?>" type="submit">
						</td>
					</tr>
    </table>

    </form>
</div>

<script>

jQuery(document).ready(function(){

    wpspjq('.wpsp-autocomplete-drop-down-panel').hide();

    wpspjq('#wpsp_agent_frm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
          e.preventDefault();
          return false;
        }
    });

    wpspjq('#wpsp-agent').click(function(){
        wpspjq('#wpsp-agent-id').val('');
        wpspjq('#wpsp-autocomplete-search').val('');
        wpspjq('.wpsp-autocomplete-drop-down-panel').toggle();
        wpspjq('#wpsp-autocomplete-search').focus();
        wpsp_search_users_for_add_agent();
    });

    wpspjq('[name="wpsp_agent[agent_id]"]').val("");

    wpspjq(document).on('click', '.delete-row', function () {
        wpspjq(this).closest("tr").remove();

    });

});

function wpsp_caa_rule_form(e){
		var error_flag = true;

		if(wpspjq.trim(wpspjq('input[name="wpsp_caa_rule[title]"').val())==""){
				alert(wpsp_caa_data.condition_name);
				error_flag=false;
		}

		var rows= wpspjq('#wpsp_selected_agents tbody tr').length;
		if(error_flag && rows==0){
				alert(wpsp_caa_data.agent_required);
				error_flag=false;
		}

		if(wpspjq.trim(wpspjq('#wpsp_en_condition_container').html())==""){
				alert(wpsp_caa_data.active_rule);
				error_flag=false;
		}
		
		if(error_flag){
			var data = new FormData(wpspjq('#wpsp_frm_en_edit')[0]);
			wpspjq.ajax({
	        type: 'post',
	        url: wpsp_caa_data.ajax_url,
	        data: data,
	        processData: false,
	        contentType: false,
	        success: function(response) {
						window.location.href=response;
	       	}
	    });
		}
	 	e.preventDefault();
		
}
</script>
