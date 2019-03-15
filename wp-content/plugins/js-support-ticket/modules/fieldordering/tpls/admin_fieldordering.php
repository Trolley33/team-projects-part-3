<script type="text/javascript">
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('categoryid').value = '';
        document.getElementById('type').value = '';
        document.getElementById('jssupportticketform').submit();
    }
    jQuery(document).ready(function () {
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#userpopupblack").show();
            var f = jQuery(this).attr('data-id');
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'fieldordering', task: 'getOptionsForFieldEdit',field:f}, function (data) {
                if(data){    
                    var abc = jQuery.parseJSON(data)                
                    jQuery("div#userpopup").html("");
                    jQuery("div#userpopup").html(abc);
                }
            });
            jQuery("div#userpopup").slideDown('slow');
        });
        jQuery("span.close, div#userpopupblack").click(function (e) {
            jQuery("div#userpopup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
    });
    function close_popup(){
        jQuery("div#userpopup").slideUp('slow', function () {
            jQuery("div#userpopupblack").hide();
        });
    }
</script>
<?php JSSTmessage::getMessage(); ?>
<?php
$type = array(
    (object) array('id' => '1', 'text' => __('Public', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Private', 'js-support-ticket'))
);
?>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">	
    <span class="js-adminhead-title"><a class="jsanchor-backlink" href="<?php echo admin_url('admin.php?page=jssupportticket&jstlay=controlpanel');?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/back-icon.png" /></a><span class="jsheadtext"><?php echo __('Field Ordering', 'js-support-ticket') ?></span>
        <a class="js-add-link button" href="?page=fieldordering&jstlay=adduserfeild&&fieldfor=<?php echo jssupportticket::$_data['fieldfor']; ?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add User field', 'js-support-ticket'); ?></a>
        </span>
        <div id="userpopupblack" style="display:none;"></div>
        <div id="userpopup" style="display:none;">
        </div>
        <?php if (!empty(jssupportticket::$_data[0])) { ?>  		
            <table id="js-support-ticket-table">
                <tr class="js-support-ticket-table-heading">
                    <th><?php echo __('S.No', 'js-support-ticket'); ?></th>
                    <th class="left"><?php echo __('Field Title', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Publish', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Visitor Publish', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Required', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Ordering', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                </tr>
                <?php
                $i = 0;
                $count = count(jssupportticket::$_data[0]) - 1;
                foreach (jssupportticket::$_data[0] AS $field) {
                    $alt = $field->published ? __('Published','js-support-ticket') : __('Unpublished','js-support-ticket');
                    $reqalt = $field->required ? __('Required','js-support-ticket') : __('Not required','js-support-ticket');
                    ?>			
                    <tr>
                        <td>
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('S.No','js-support-ticket'); ?>:</span>
                        <?php echo $field->id; ?></td>
                        <td class="left">
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('Field Title','js-support-ticket'); ?>:</span>
                            <?php 
                                if ($field->fieldtitle) 
                                    echo '<a id="userpopup" data-id='.$field->id.'>'.__($field->fieldtitle,'js-support-ticket').'</a>';
                                else echo $field->userfieldtitle;
                                if($field->cannotunpublish == 1){
                                    echo '<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>';
                                }
                                if($field->field == 'assignto' || $field->field == 'internalnotetitle' || $field->field == 'premade' || $field->field == 'helptopic' || $field->field == 'duedate'){
                                    echo '<font style="color:#ff0000;font-size:20px;margin:0px 5px;">*</font>';
                                }
                            ?>
                        </td>
                        <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('User Publish','js-support-ticket'); ?>:</span>
                            <?php if ($field->cannotunpublish == 1) { ?>
                                <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo __('Can Not Unpublished','js-support-ticket'); ?>" />
                            <?php }elseif ($field->published == 1) {
                                 $url  = "?page=fieldordering&task=changepublishstatus&action=jstask&status=unpublish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo $alt; ?>" /></a> 
                            <?php }else{
                                $url  = "?page=fieldordering&task=changepublishstatus&action=jstask&status=publish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/cross.png'; ?>" title="<?php echo $alt; ?>" /></a> 
                             <?php } ?>
                        </td>
                        <td>
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('Visitor Publish','js-support-ticket'); ?>:</span>
                            <?php if ($field->cannotunpublish == 1) { ?>
                                <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo __('Can Not Unpublished','js-support-ticket'); ?>" />
                            <?php }elseif ($field->isvisitorpublished == 1) { 
                                $url  = "?page=fieldordering&task=changevisitorpublishstatus&action=jstask&status=unpublish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-visitor-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo $alt; ?>" /></a> 
                            <?php }else{ 
                                $url  = "?page=fieldordering&task=changevisitorpublishstatus&action=jstask&status=publish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-visitor-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/cross.png'; ?>" title="<?php echo $alt; ?>" /></a>
                            <?php } ?>    
                        </td>
                        <td>
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('Required','js-support-ticket'); ?>:</span>
                            <?php if ($field->cannotunpublish == 1) { ?>
                                <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo __('Can Not mark as not required','js-support-ticket'); ?>" />
                            <?php }elseif ($field->required == 1) { 
                                $url  = "?page=fieldordering&task=changerequiredstatus&action=jstask&status=unrequired&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-required-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/tick.png'; ?>" title="<?php echo $alt; ?>" /></a>
                            <?php }else{
                                $url  = "?page=fieldordering&task=changerequiredstatus&action=jstask&status=required&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-required-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/cross.png'; ?>" title="<?php echo $reqalt; ?>" /></a>
                            <?php } ?>
                        </td>
                        <td>
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('Ordering','js-support-ticket'); ?>:</span>
                            <?php if ($i != $count) {
                                  $url  = "?page=fieldordering&task=changeorder&action=jstask&order=down&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-order'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/downarrow.png'; ?>" /></a>  
                             <?php } ?>
                            <?php echo $field->ordering; ?>
                            <?php if ($i > 0) {
                                $url  = "?page=fieldordering&task=changeorder&action=jstask&order=up&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                     ?>
                                    <a href="<?php echo wp_nonce_url($url, 'change-order'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/uparrow.png'; ?>" /></a> 
                            <?php } ?>
                        </td>
                        <td>
                        <span class="js-support-ticket-table-responsive-heading"><?php echo __('Action','js-support-ticket'); ?>:</span>
                            <?php
                                if($field->isuserfield==1){
                                    echo '<a href="?page=fieldordering&jstlay=adduserfeild&jssupportticketid='.$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'].'"><img src="'.jssupportticket::$_pluginpath.'includes/images/edit.png" /></a>&nbsp;';
                                    echo '<a onclick="return confirm(\''.__('Are you sure to delete','js-support-ticket').'\');" href="?page=fieldordering&task=removeuserfeild&action=jstask&jssupportticketid='.$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'].'"><img src="'.jssupportticket::$_pluginpath.'includes/images/remove.png" /></a>';
                                }else{
                                    echo '---';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>		
            <div class="js-form-button">
                <?php echo '<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>'.__('Cannot unpublish field','js-support-ticket'); ?>
                </br>
                <?php echo '<font style="color:#ff0000;font-size:20px;margin:0px 5px;">*</font>'.__('Pro Version Only','js-support-ticket'); ?>
            </div>
            <?php
            /*
              if ( jssupportticket::$_data[1] ) {
              echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . jssupportticket::$_data[1] . '</div></div>';
              }
             */
        } else {
            JSSTlayout::getNoRecordFound();
        }
        ?>
    </div>
</div>
