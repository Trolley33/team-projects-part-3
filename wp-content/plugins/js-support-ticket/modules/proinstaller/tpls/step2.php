
<div id="js-tk-admin-wrapper">
    <div id="js-tk-cparea">
        <div id="jsst-main-wrapper" >
            <div id="jsst-lower-wrapper">
                <div class="jsst_installer_wrapper" id="jsst-installer_id">    
                    <div class="jsst_top">
                        <div class="jsst_logo_wrp">
                            <img src="<?php echo jssupportticket::$_pluginpath.'includes/images/installer/logo.png';?>">
                        </div>
                        <div class="jsst_heading_text"><?php echo __("JS Support Ticket Pro",'js-support-ticket'); ?></div>
                        <div class="jsst_subheading_text"><?php echo __("Most Powerful Wordpress Help Desk Plugin",'js-support-ticket'); ?></div>
                    </div>
                    <div class="jsst_middle" id="jsst_middle">
                        <div class="jsst_form_field_wrp">
                            <div class="jsst_bg_overlay">
                                <input type="text" name="transactionkey" id="transactionkey" class="jsst_key_field" value="<?php echo isset(jssupportticket::$_data['transactionkey']) ? jssupportticket::$_data['transactionkey'] : '';?>" placeholder="<?php echo __('Activation key','js-support-ticket'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if(jssupportticket::$_data['response'] != " "){
                        $response = base64_decode(jssupportticket::$_data['response']);
                        $response = json_decode($response);?>
                        <div class="jsst_error_messages">
                          <?php if($response[0] != 1){ ?>
                            <span class="jsst_msg" id="jsst_error_message"><?php echo $response[1]?></span><?php  
                          }else{ ?>
                            <div id="jsst_next_form"><?php echo $response[1]?></div><?php 
                          } ?>
                        </div>                     
                    <?php  } ?> 
                </div>
            </div>
        </div>        
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('div#jsst_middle').hide();
        $('span#jsjob_installer_helptext').hide();
        $('div#jsjob_installer_formlabel').hide();
    });    
</script>

