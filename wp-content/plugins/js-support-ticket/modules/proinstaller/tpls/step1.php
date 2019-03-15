<div id="js-tk-admin-wrapper">
    <div id="js-tk-cparea">
        <div style="display:none;" id="jsjob_installer_waiting_div"></div>
        <span style="display:none;" id="jsjob_installer_waiting_span"><?php echo __("Please wait installation in progress",'js-support-ticket'); ?></span>
        <div id="jsst-main-wrapper" >
            <div id="jsst-lower-wrapper">
                <div class="jsst_installer_wrapper" id="jsst-installer_id">    
                    <div class="jsst_top">
                        <div class="jsst_logo_wrp">
                            <img src="<?php echo jssupportticket::$_pluginpath.'includes/images/installer/logo.png';?>">
                        </div>
                        <div class="jsst_heading_text"><?php echo __("JS Support Ticket Pro",'js-support-ticket'); ?></div>
                        <div class="jsst_subheading_text"><?php echo __("Most Poweful Wordpress Help Desk Plugin",'js-support-ticket'); ?></div>
                    </div>
                    <form action="<?php echo admin_url('admin.php?page=proinstaller&task=getversionlist'); ?>" method="post">
                        <div class="jsst_middle" id="jsst_middle">
                            <div class="jsst_form_field_wrp">
                                <div class="jsst_bg_overlay">
                                    <input type="text" name="transactionkey" id="transactionkey" class="jsst_key_field" value="<?php echo isset(jssupportticket::$_data['transactionkey']) ? jssupportticket::$_data['transactionkey'] : '';?>" placeholder="<?php echo __('Activation key','js-support-ticket'); ?>"/>
                                </div>
                            </div>
                        </div>
                        <?php if(isset(jssupportticket::$_data['response'])){ ?>
                            <div id="invalid_activation_key" class="jsst_error_messages">
                                 <span class="jsst_msg"><?php echo jssupportticket::$_data['response']; ?></span>
                            </div>
                        <?php } ?>
                         <?php if (jssupportticket::$_data['phpversion'] < 5) { ?>
                            <div class="jsst_error_messages">
                                <span class="jsst_msg"><?php echo __('PHP version smaller then recomended', 'js-support-ticket'); ?></span>
                            </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['curlexist'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('CURL not exist', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['gdlib'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('GD library not exist', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['ziplib'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Zip library not exist', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['dir'] < 755) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Directory permissions error', 'js-support-ticket'); ?>&nbsp;"<?php echo jssupportticket::$_path; ?>"&nbsp;<?php echo __('directory is not writeable','js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['tmpdir'] < 755) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Directory permissions error', 'js-support-ticket'); ?>&nbsp;"<?php echo ABSPATH.'/tmp'; ?>"&nbsp;<?php echo __('directory is not writeable','js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['create_table'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Database create table not allowed', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['insert_record'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Database insert record not allowed', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['update_record'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Database update record not allowed', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['delete_record'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Database delete record not allowed', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['drop_table'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Database drop table not allowed', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jssupportticket::$_data['step2']['file_downloaded'] != 1) { ?>
                                <div class="jsst_error_messages">
                                    <span class="jsst_msg"><?php echo __('Error file not downloaded', 'js-support-ticket'); ?></span>
                                </div>
                        <?php } ?>
                        <div class="jsst_bottom">
                            <div class="jsst_submit_btn">
                            <?php if ((jssupportticket::$_data['phpversion'] > 5) && (jssupportticket::$_data['curlexist'] == 1) && (jssupportticket::$_data['gdlib'] == 1) && (jssupportticket::$_data['ziplib'] == 1) && (jssupportticket::$_data['step2']['dir'] >= 755 ) && (jssupportticket::$_data['step2']['tmpdir'] >= 755 ) && (jssupportticket::$_data['step2']['create_table'] == 1) && (jssupportticket::$_data['step2']['insert_record'] == 1) && (jssupportticket::$_data['step2']['update_record'] == 1 ) && (jssupportticket::$_data['step2']['delete_record'] == 1 ) && (jssupportticket::$_data['step2']['drop_table'] == 1 ) && (jssupportticket::$_data['step2']['file_downloaded'] == 1 )) { ?>
                                <button type="submit" class="jsst_btn" role="submit"><?php echo __("Start","js-support-ticket"); ?></button>
                            <?php }else{ ?>
                                <button type="button" class="jsst_btn" role="submit"><?php echo __("Start","js-support-ticket"); ?></button>
                            <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="serialnumber" id="serialnumber" value="" />
                        <input type="hidden" name="productcode" id="productcode" value="<?php echo isset(jssupportticket::$_data['productcode']) ? jssupportticket::$_data['productcode'] : 'jssupportticket'; ?>" />
                        <input type="hidden" name="productversion" id="productversion" value="<?php echo isset(jssupportticket::$_data['versioncode']) ? jssupportticket::$_data['versioncode'] : '1.0.2'; ?>" />
                        <input type="hidden" name="producttype" id="producttype" value="<?php echo isset(jssupportticket::$_data['producttype']) ? jssupportticket::$_data['producttype'] : 'free'; ?>" />
                        <input type="hidden" name="domain" id="domain" value="<?php echo site_url(); ?>" />
                        <input type="hidden" name="JVERSION" id="JVERSION" value="<?php echo get_bloginfo('version'); ?>" />
                        <input type="hidden" name="installerversion" value="1.0" />
                        <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                    </form> 
                </div>
            </div>
        </div>        
    </div>
</div>
