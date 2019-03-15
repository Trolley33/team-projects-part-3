<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="tab_container">

    <form id="wpsp_frm_en_add" method="post" action="admin.php?page=wp-support-plus&setting=addons&section=caa-settings&wpsp_caa_action=insert_caa_rule">

      <table class="form-table">

          <tr>
            <th scope="row"><h3><?php _e( 'Add Condition', 'wpsp-caa' );?></h3></th>
            <td></td>
          </tr>

          <tr>
              <th scope="row"><?php _e( 'Title', 'wpsp-caa' );?></th>
              <td>
                <input type="text" name="wpsp_caa_rule[title]" />
              </td>
          </tr>

    </table>


      <p class="submit">
          <input id="submit" class="button button-primary" name="submit" value="<?php _e( 'Save & Continue', 'wpsp-caa' );?>" type="submit">
      </p>

    </form>

</div>
