<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpspcaa;
$wpsp_caa_rule = get_option('wpsp_caa_rule') ? get_option('wpsp_caa_rule') : array();

?>
<br />
<div id="tab_container" style="clear:both;">

  <input type="hidden" name="action" value="update"/>
  <input type="hidden" name="update_setting" value="settings_caa"/>
  <?php wp_nonce_field('wpbdp_tab_general_section_general'); ?>

  <form id="wpsp_frm_email_notification_settings" method="post" action="">
    
    <div style="clear: both; text-align: right;padding: 5px 15px;">
        <a href="admin.php?page=wp-support-plus&setting=addons&section=caa-settings&wpsp_caa_action=add_caa_rule" class="button button-primary"><?php _e( '+ Add New Rule', 'wpsp-caa' );?></a>
    </div>
    
    <table>

      <tr>
          <td colspan="2">
            <table id="wpsp_en_tbl_notifications" class="wp-list-table widefat fixed posts">
              <thead>
                <tr>
                  <th class="wpsp_en_title_col" scope="col"><?php _e( 'Title', 'wpsp-caa' );?></th>
                  <th class="wpsp_en_type_col" scope="col"><?php _e( 'Assigned Agents', 'wpsp-caa' );?></th>
                  <th scope="col" style="width:110px;"><?php _e( 'Actions', 'wpsp-caa' );?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($wpsp_caa_rule as $key => $value):
                          $comp_users=$wpspcaa->functions->get_assigned_agents_to_rule($value);
                          $users_name=array();
                          foreach ($comp_users as $user){
                              $users_name[]=$user->display_name;
                          }
                          $names=implode( ', ', $users_name);
                  ?>
                  <tr>
                    <td><?php echo $value['title']; ?></td>
                    <td><?php echo $names; ?></td>
                    <td>
                      <a href="admin.php?page=wp-support-plus&setting=addons&section=caa-settings&wpsp_caa_action=edit_setting&rule_id=<?php echo $key;?>"><?php _e('Edit','wpsp-caa')?></a>&nbsp;|
                      <a href="#" onclick="wpsp_caa_delete(this)" data-href="admin.php?page=wp-support-plus&setting=addons&section=caa-settings&wpsp_caa_action=delete_setting&rule_id=<?php echo $key;?>&nonce=<?php echo wp_create_nonce($key);?>" class="wpsp_en_delete"><?php _e('Delete','wpsp-caa')?></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
          </td>
      </tr>
    </table>
  </form>
