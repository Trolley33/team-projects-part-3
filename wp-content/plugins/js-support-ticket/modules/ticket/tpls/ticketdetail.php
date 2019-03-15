
<?php
if (jssupportticket::$_config['offline'] == 2) {
    if (get_current_user_id() != 0 || jssupportticket::$_config['visitor_can_create_ticket'] == 1) {
        JSSTmessage::getMessage();
        wp_enqueue_script('file_validate.js', jssupportticket::$_pluginpath . 'includes/js/file_validate.js');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery.cluetip.min.js', jssupportticket::$_pluginpath . 'includes/js/jquery.cluetip.min.js');
        wp_enqueue_script('jquery.hoverIntent.js', jssupportticket::$_pluginpath . 'includes/js/jquery.hoverIntent.js');
        wp_enqueue_style('jquery.cluetip', jssupportticket::$_pluginpath . 'includes/css/jquery.cluetip.css');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                //$('img.tooltip').cluetip({splitTitle: '|'});
                jQuery("#tabs").tabs();
                jQuery("#tk_attachment_add").click(function () {
                    var obj = this;
                    var current_files = jQuery('input[type="file"]').length;
                    var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
                    var append_text = "<span class='tk_attachment_value_text'><input name='filename[]' type='file' onchange=\"uploadfile(this,'<?php echo jssupportticket::$_config['file_maximum_size']; ?>','<?php echo jssupportticket::$_config['file_extension']; ?>');\" size='20' maxlenght='30'  /><span  class='tk_attachment_remove'></span></span>";
                    if (current_files < total_allow) {
                        jQuery(".tk_attachment_value_wrapperform").append(append_text);
                    } else if ((current_files === total_allow) || (current_files > total_allow)) {
                        alert("<?php echo __('File upload limit exceed', 'js-support-ticket'); ?>");
                        obj.hide();
                    }
                });
                jQuery(document).delegate(".tk_attachment_remove", "click", function (e) {
                    jQuery(this).parent().remove();
                    var current_files = jQuery('input[type="file"]').length;
                    var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
                    if (current_files < total_allow) {
                        jQuery("#tk_attachment_add").show();
                    }
                });
                jQuery("a#showaction").click(function (e) {
                    e.preventDefault();
                    jQuery("div#action-div").slideToggle();
                });
                jQuery("div#popup-back,span.close-history").click(function (e) {
                    jQuery("div#userpopup").slideUp('slow');
                    setTimeout(function () {
                        jQuery('div#popup-back').hide();
                    }, 700);
                });
            });
            function actionticket(action) {
                /*  Action meaning
                 * 1 -> Change Priority
                 * 2 -> Close Ticket
                 * 2 -> Reopen Ticket
                 */
                jQuery("input#actionid").val(action);
                jQuery("form#adminTicketform").submit();
            }
            function checktinymcebyid(id) {
                var content = tinymce.get(id).getContent({format: 'text'});
                if (jQuery.trim(content) == '')
                {
                    alert("<?php echo __('Some values are not acceptable please retry', 'js-support-ticket'); ?>");
                    return false;
                }
                return true;
            }
        </script>
        <div id="popup-back" style="display:none;"> </div>
        <span style="display:none" id="filesize"><?php echo __('Error file size too large', 'js-support-ticket'); ?></span>
        <span style="display:none" id="fileext"><?php echo __('Error file ext mismatch', 'js-support-ticket'); ?></span>
        <?php JSSTbreadcrumbs::getBreadcrumbs(); ?>
        <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
        <?php
        if (!empty(jssupportticket::$_data[0])) {
            if (jssupportticket::$_data[0]->status == 0) {
                $style = "red;";
                $status = __('New', 'js-support-ticket');
            } elseif (jssupportticket::$_data[0]->status == 1) {
                $style = "orange;";
                $status = __('Waiting reply', 'js-support-ticket');
            } elseif (jssupportticket::$_data[0]->status == 3) {
                $style = "green;";
                $status = __('Replied', 'js-support-ticket');
            } elseif (jssupportticket::$_data[0]->status == 4) {
                $style = "blue;";
                $status = __('Closed', 'js-support-ticket');
            }

            $cur_uid = get_current_user_id();
            $link = jssupportticket::makeUrl(array('jstmod'=>'reply', 'task'=>'savereply'));
            $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
            ?>
            <div class="js-ticket-ticket-detail-wrapper">
                <form method="post" action="<?php echo esc_url($link); ?>" id="adminTicketform" enctype="multipart/form-data">
                    <div class="js-col-md-12 js-ticket-detail-wrapper"> <!-- Ticket Detail Data Top -->
                        <div class="js-ticket-detail-box"><!-- Ticket Detail Box -->
                            <div class="js-ticket-detail-left"><!-- Left Side Image -->
                                <div class="js-ticket-user-img-wrp">
                                    <?php if (isset(jssupportticket::$_data[0]->uid) && !empty(jssupportticket::$_data[0]->uid)) {
                                        echo get_avatar(jssupportticket::$_data[0]->uid,120);
                                    } else { ?>
                                        <img src="<?php echo jssupportticket::$_pluginpath . '/includes/images/ticketmanbig.png'; ?>" />
                                    <?php } ?>                            
                                </div>
                                <div class="js-ticket-user-name-wrp">
                                    <?php echo jssupportticket::$_data[0]->name; ?>
                                </div>
                                <div class="js-ticket-user-email-wrp">
                                    <?php echo jssupportticket::$_data[0]->email; ?>
                                </div>
                                <div class="js-ticket-user-email-wrp">
                                    <?php echo jssupportticket::$_data[0]->phone; ?>
                                </div>
                            </div>
                            <div class="js-ticket-detail-right"><!-- Right Side Ticket Data -->
                                <div class="js-ticket-rows-wrp" >
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                            <?php echo __($field_array['subject'], 'js-support-ticket');?><?php echo __(' :', 'js-support-ticket');?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                           <span class="js-ticket-subject-link"><?php echo __(jssupportticket::$_data[0]->subject ,'js-support-ticket'); ?></span>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                            <?php echo __($field_array['department'], 'js-support-ticket'); ?><?php echo __(' :', 'js-support-ticket');?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                            <?php echo __(jssupportticket::$_data[0]->departmentname ,'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                           <?php echo __('Created :', 'js-support-ticket') . ' '; ?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                           <?php
                                                $startTimeStamp = strtotime(jssupportticket::$_data[0]->created);
                                                $endTimeStamp = strtotime("now");
                                                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                                $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
                                                // and you might want to convert to integer
                                                $numberDays = intval($numberDays);
                                                if ($numberDays != 0 && $numberDays == 1) {
                                                    $day_text = __('Day', 'js-support-ticket');
                                                } elseif ($numberDays > 1) {
                                                    $day_text = __('Days', 'js-support-ticket');
                                                } elseif ($numberDays == 0) {
                                                    $day_text = __('Today', 'js-support-ticket');
                                                }
                                                if ($numberDays == 0) {
                                                    echo $day_text;
                                                } else {
                                                    echo $numberDays . ' ' . $day_text . ' ';
                                                    echo __('Ago , ', 'js-support-ticket');
                                                }
                                            ?>
                                           <?php echo ' ' . date_i18n("d F, Y", strtotime(jssupportticket::$_data[0]->created)); ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                           <?php echo __('Ticket ID :', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                           <?php echo jssupportticket::$_data[0]->ticketid; ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                           <?php echo __($field_array['priority'], 'js-support-ticket'); ?><?php echo __(' :', 'js-support-ticket');?>
                                        </div>
                                        <div class="js-ticket-field-value js-ticket-priorty" style="background:<?php echo jssupportticket::$_data[0]->prioritycolour;?>; color:#ffffff;">
                                           <?php echo __(jssupportticket::$_data[0]->priority, 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                           <?php echo __('Last Reply :', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                           <?php if (empty(jssupportticket::$_data[0]->lastreply) || jssupportticket::$_data[0]->lastreply == '0000-00-00 00:00:00') echo __('No Last Reply', 'js-support-ticket');
                                                else echo date_i18n(jssupportticket::$_config['date_format'], strtotime(jssupportticket::$_data[0]->lastreply)); ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <div class="js-ticket-field-title">
                                           <?php echo __($field_array['status'], 'js-support-ticket'); ?><?php echo __(' :', 'js-support-ticket');?>
                                        </div>
                                        <div class="js-ticket-field-value">
                                           <?php
                                                if (jssupportticket::$_data[0]->status == 4 || jssupportticket::$_data[0]->status == 5 )
                                                    $ticketmessage = __('Closed', 'js-support-ticket');
                                                elseif (jssupportticket::$_data[0]->status == 2)
                                                    $ticketmessage = __('In Progress', 'js-support-ticket');
                                                else
                                                    $ticketmessage = __('Open', 'js-support-ticket');
                                                $printstatus = 1;
                                                if (jssupportticket::$_data[0]->lock == 1) {
                                                    echo '<div class="js-ticket-status-note">' . __('Lock', 'js-support-ticket').' '. __(',', 'js-support-ticket') . '</div>';
                                                    $printstatus = 0;
                                                }
                                                if (jssupportticket::$_data[0]->isoverdue == 1) {
                                                    echo '<div class="js-ticket-status-note">' . __('Overdue', 'js-support-ticket') . '</div>';
                                                    $printstatus = 0;
                                                }
                                                if ($printstatus == 1) {
                                                    echo $ticketmessage;
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-row">
                                        <?php
                                            $customfields = JSSTincluder::getObjectClass('customfields')->userFieldsData(1);
                                            foreach ($customfields as $field) {
                                                $array =  JSSTincluder::getObjectClass('customfields')->showCustomFields($field,3, jssupportticket::$_data[0]->params);

                                                if($field->userfieldtype=='file'){
                                                    $fvalue = $array['value'];
                                                $html = '<span class="js-ticket-title">' . __($field->fieldtitle,'js-support-ticket') . ':&nbsp</span>';
                                                   if($fvalue !=null){
                                                        $path = admin_url("?page=ticket&action=jstask&task=downloadbyname&id=".jssupportticket::$_data['custom']['ticketid']."&name=".$fvalue);
                                                        $html .= '
                                                            <div class="js_ticketattachment">
                                                                ' . __($field->fieldtitle,'js-support-ticket') . ' ( ' . $fvalue . ' ) ' . '              
                                                                <a class="button" target="_blank" href="' . esc_url($path) . '">' . __('Download', 'js-support-ticket') . '</a>
                                                            </div>';
                                                        $array['value'] = $html;
                                                    
                                                    }
                                                }elseif($field->userfieldtype=='date' && !empty($fvalue)){
                                                    $fvalue = $array['value'];
                                                    $fvalue = date_i18n(jssupportticket::$_config['date_format'],strtotime($fvalue));
                                                    $html .=    '<span class="js-ticket-title textstylebold">' . __($field->fieldtitle,'js-support-ticket') . ':&nbsp</span>
                                                            <span class="js-ticket-value">' . $fvalue . '</span>';
                                                    $array['value'] = $html;
                                                }   


                                                 ?>
                                                    <div class="js-ticket-row">
                                                        <div class="js-ticket-field-title">
                                                            <?php echo  __($array['title'],'js-support-ticket') .' :';?>
                                                        </div>
                                                        <div class="js-ticket-field-value">
                                                            <?php echo $array['value'];
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <?php
                                    if (jssupportticket::$_data[0]->status == 0) {
                                        $color = "#9ACC00;";
                                        $ticketmessage = __('Open', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #9ACC00";
                                    } elseif (jssupportticket::$_data[0]->status == 1) {
                                        $color = "#217ac3;";
                                        $ticketmessage = __('On Waiting', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #217ac3";
                                    } elseif (jssupportticket::$_data[0]->status == 2) {
                                        $color = "#FE7C2C;";
                                        $ticketmessage = __('In Progress', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #FE7C2C";
                                    } elseif (jssupportticket::$_data[0]->status == 3) {
                                        $color = "#FFB613;";
                                        $ticketmessage = __('Replied', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #FFB613";
                                    } elseif (jssupportticket::$_data[0]->status == 4) {
                                        $color = "#F04646;";
                                        $ticketmessage = __('Closed', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #F04646";
                                    } elseif (jssupportticket::$_data[0]->status == 5) {
                                        $color = "#F04646;";
                                        $ticketmessage = __('Closed and Merged', 'js-support-ticket');
                                        $boxshadow = "0px 0px 1px 1px #F04646";
                                    }
                                    ?>
                                    <div class="js-ticket-openclosed-box" style="background-color:<?php echo $color;?>; box-shadow:<?php echo $boxshadow;?>;">
                                        <?php echo $ticketmessage; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-ticket-action-btn-wrp"><!-- Ticket Action Button -->
                            <?php if (jssupportticket::$_data[0]->status != 4) { ?>
                                <div class="js-ticket-btn-box">
                                    <a class="js-button" onclick="return confirm('<?php echo __('Are you sure to close ticket', 'js-support-ticket'); ?>');" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'closeticket', 'action'=>'jstask', 'jsstpageid'=>get_the_ID(), 'ticketid'=>jssupportticket::$_data[0]->id))); ?>">
                                        <img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticketdetailicon/close.png" title="<?php echo __('Close', 'js-support-ticket'); ?>" /><?php echo __('Close', 'js-support-ticket'); ?>
                                    </a>
                                </div>
                            <?php } else {
                                if (JSSTincluder::getJSModel('ticket')->checkCanReopenTicket(jssupportticket::$_data[0]->id)) {
                                    $link = jssupportticket::makeUrl(array('jstmod'=>'ticket', 'action'=>'jstask', 'jsstpageid'=>get_the_ID(), 'task'=>'reopenticket', 'ticketid'=>jssupportticket::$_data[0]->id));
                                    ?>
                                    <div class="js-ticket-btn-box">
                                        <a class="js-button" href="<?php echo esc_url($link); ?>" alt="<?php echo __('Reopen Ticket', 'js-support-ticket'); ?>">
                                            <img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/ticketdetailicon/reopen.png" title="<?php echo __('Reopen', 'js-support-ticket'); ?>" /><?php echo __('Reopen', 'js-support-ticket'); ?>
                                        </a>
                                    </div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="js-ticket-post-reply-wrapper"><!-- Ticket Post Replay -->
                        <div class="js-ticket-thread-heading"><?php echo __('Ticket Thread', 'js-support-ticket'); ?></div> <!-- Heading -->
                        <div class="js-ticket-detail-box js-ticket-post-reply-box"><!-- Ticket Detail Box -->
                            <div class="js-ticket-detail-left js-ticket-white-background"><!-- Left Side Image -->
                                <div class="js-ticket-user-img-wrp">
                                    <?php if(isset(jssupportticket::$_data[0]->uid) && !empty(jssupportticket::$_data[0]->uid)) {
                                            echo get_avatar(jssupportticket::$_data[0]->uid);
                                        } else { ?>
                                            <img src="<?php echo jssupportticket::$_pluginpath . '/includes/images/ticketmanbig.png'; ?>" />
                                        <?php } ?>                            
                                </div>
                                <div class="js-ticket-user-name-wrp">
                                    <?php echo jssupportticket::$_data[0]->name; ?>
                                </div>
                                <div class="js-ticket-user-email-wrp">
                                    <?php echo jssupportticket::$_data[0]->email; ?>
                                </div>
                            </div>
                            <div class="js-ticket-detail-right js-ticket-background"><!-- Right Side Ticket Data -->
                                <div class="js-ticket-rows-wrapper">
                                    <div class="js-ticket-rows-wrp" >
                                        <div class="js-ticket-row">
                                            <div class="js-ticket-field-value">
                                               <?php echo  wp_kses_post(jssupportticket::$_data[0]->message); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        if (!empty(jssupportticket::$_data['ticket_attachment'])) { ?>
                                            <div class="js-ticket-attachments-wrp">
                                                <?php foreach (jssupportticket::$_data['ticket_attachment'] AS $attachment) {
                                                        $path = jssupportticket::makeUrl(array('jstmod'=>'ticket','task'=>'downloadbyid','action'=>'jstask','id'=> $attachment->id ,'jsstpageid'=>get_the_ID()));
                                                        echo '
                                                            <div class="js_ticketattachment">
                                                                <span class="js-ticket-download-file-title">
                                                                    ' . $attachment->filename . ' ( ' . round($attachment->filesize,2) . ' kb) ' . '
                                                                </span>
                                                                <a class="js-download-button" target="_blank" href="' . esc_url($path) . '">
                                                                    <img class="js-ticket-download-img" src=" '.jssupportticket::$_pluginpath .'/includes/images/ticketdetailicon/download-all.png">
                                                                </a> 
                                                            </div>';            
                                                        }
                                                    echo'
                                                        <a class="js-all-download-button" target="_blank" href="' . esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'downloadall', 'action'=>'jstask', 'downloadid'=>jssupportticket::$_data[0]->id , 'jsstpageid'=>get_the_ID()))) . '" onclick="" target="_blank"><img class="js-ticket-all-download-img" src=" '.jssupportticket::$_pluginpath .'/includes/images/ticketdetailicon/download-all.png">'. __('Download All', 'js-support-ticket') . '</a>';?>
                                            </div>
                                    <?php } ?>
                                </div>
                                <div class="js-ticket-time-stamp-wrp">
                                    <span class="js-ticket-ticket-created-date">
                                        <?php echo date_i18n("l F d, Y, h:i:s", strtotime(jssupportticket::$_data[0]->created)); ?>
                                    </span>
                                </div>    
                            </div>
                        </div>
                         <!-- User post Reply Section -->
                        <?php if (!empty(jssupportticket::$_data[4])) 
                        foreach (jssupportticket::$_data[4] AS $reply):
                            if ($cur_uid == $reply->uid) ?>
                                <div class="js-ticket-detail-box js-ticket-post-reply-box"><!-- Ticket Detail Box -->
                                    <div class="js-ticket-detail-left js-ticket-white-background"><!-- Left Side Image -->
                                        <div class="js-ticket-user-img-wrp">
                                            <?php
                                            if (isset($reply->uid) && !empty($reply->uid)) {
                                                echo get_avatar($reply->uid);
                                            } else { ?>
                                                <img src="<?php echo jssupportticket::$_pluginpath . '/includes/images/ticketmanbig.png'; ?>" />
                                            <?php } ?>                        
                                        </div>
                                        <div class="js-ticket-user-name-wrp">
                                            <?php echo $reply->name; ?>
                                        </div>
                                        <div class="js-ticket-user-email-wrp">
                                            <?php echo ($reply->ticketviaemail == 1) ? __('Created via Email', 'js-support-ticket') : ''; ?>
                                        </div>
                                    </div>
                                    <div class="js-ticket-detail-right js-ticket-background"><!-- Right Side Ticket Data -->
                                        <div class="js-ticket-rows-wrapper">    
                                            <div class="js-ticket-rows-wrp" >
                                                <div class="js-ticket-row">
                                                    <div class="js-ticket-field-value">
                                                        <?php echo  wp_kses_post(html_entity_decode($reply->message)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if (!empty($reply->attachments)) { ?>
                                                    <div class="js-ticket-attachments-wrp">
                                                        <?php foreach ($reply->attachments AS $attachment) {
                                                                $path = jssupportticket::makeUrl(array('jstmod'=>'ticket','task'=>'downloadbyid','action'=>'jstask','id'=> $attachment->id ,'jsstpageid'=>get_the_ID()));
                                                                echo '
                                                                    <div class="js_ticketattachment">
                                                                        <span class="js-ticket-download-file-title">
                                                                            ' . $attachment->filename . ' ( ' . round($attachment->filesize) . ' kb) ' . '
                                                                        </span>
                                                                        <a class="js-download-button" target="_blank" href="' . esc_url($path) . '">
                                                                            <img class="js-ticket-download-img" src=" '.jssupportticket::$_pluginpath .'/includes/images/ticketdetailicon/download-all.png">
                                                                        </a> 
                                                                    </div>';            
                                                                }
                                                            echo'
                                                                <a class="js-all-download-button" target="_blank" href="' . esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'downloadall', 'action'=>'jstask', 'downloadid'=>jssupportticket::$_data[0]->id , 'jsstpageid'=>get_the_ID()))) . '" onclick="" target="_blank"><img class="js-ticket-all-download-img" src=" '.jssupportticket::$_pluginpath .'/includes/images/ticketdetailicon/download-all.png">'. __('Download All', 'js-support-ticket') . '</a>';?>
                                                    </div>
                                                <?php } ?>
                                        </div>
                                        <div class="js-ticket-time-stamp-wrp">
                                            <span class="js-ticket-ticket-created-date">
                                                <?php echo date_i18n("l F d, Y, h:i:s", strtotime($reply->created)); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <?php echo JSSTformfield::hidden('actionid', ''); ?>
                    <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                    <?php echo JSSTformfield::hidden('created', jssupportticket::$_data[0]->created); ?>
                    <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                    <?php echo JSSTformfield::hidden('updated', jssupportticket::$_data[0]->updated); ?>
                    <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                </form>
            </div>
            <div class="js-ticket-reply-forms-wrapper"><!-- Ticket Reply Forms Wrapper -->
                <?php if (jssupportticket::$_data[0]->status != 4) { ?>
                    <div class="js-ticket-reply-forms-heading"><?php echo __('Reply a message', 'js-support-ticket'); ?></div>
                    <form class="post-reply-textarea"  method="post" action="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'reply', 'task'=>'savereply'))); ?>" enctype="multipart/form-data">
                        <div id="postreply" class="js-ticket-post-reply">
                            <div class="js-ticket-reply-field-wrp">
                                <div class="js-ticket-reply-field"><?php echo wp_editor('', 'jsticket_message', array('media_buttons' => false)); ?></div>
                            </div>
                            <div class="js-ticket-reply-attachments"><!-- Attachments -->
                                <div class="js-attachment-field-title"><?php echo __($field_array['attachments'], 'js-support-ticket'); ?></div>
                                <div class="js-attachment-field">
                                    <div class="tk_attachment_value_wrapperform tk_attachment_user_reply_wrapper">
                                        <span class="tk_attachment_value_text">
                                            <input type="file" class="inputbox js-attachment-inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo jssupportticket::$_config['file_maximum_size']; ?>', '<?php echo jssupportticket::$_config['file_extension']; ?>');" size="20" maxlenght='30'/>
                                            <span class='tk_attachment_remove'></span>
                                        </span>
                                    </div>  
                                    <span class="tk_attachments_configform">
                                        <?php echo __('Maximum File Size', 'js-support-ticket');
                                              echo ' (' . jssupportticket::$_config['file_maximum_size']; ?>KB)<br><?php echo __('File Extension Type', 'js-support-ticket');
                                              echo ' (' . jssupportticket::$_config['file_extension'] . ')'; ?>
                                    </span>
                                    <span id="tk_attachment_add" data-ident="tk_attachment_user_reply_wrapper" class="tk_attachments_addform"><?php echo __('Add more', 'js-support-ticket'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="js-ticket-closeonreply-wrp">
                            <div class="js-ticket-closeonreply-title"><?php echo __('Ticket Status', 'js-support-ticket'); ?></div>
                            <div class="replyFormStatus js-form-title-position-reletive-left">
                                <?php echo JSSTformfield::checkbox('closeonreply', array('1' => __('Close on reply', 'js-support-ticket')), '', array('class' => 'radiobutton js-ticket-closeonreply-checkbox')); ?>
                            </div>
                        </div>
                        <div class="js-ticket-reply-form-button-wrp">
                            <?php echo JSSTformfield::submitbutton('postreply', __('Post Reply', 'js-support-ticket'), array('class' => 'button js-ticket-save-button', 'onclick' => "return checktinymcebyid('message');")); ?>
                        </div>  
                        <?php echo JSSTformfield::hidden('departmentid', jssupportticket::$_data[0]->departmentid); ?>
                        <?php echo JSSTformfield::hidden('ticketid', jssupportticket::$_data[0]->id); ?>
                        <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                        <?php echo JSSTformfield::hidden('ticketrandomid', jssupportticket::$_data[0]->ticketid); ?>
                        <?php echo JSSTformfield::hidden('hash', jssupportticket::$_data[0]->hash); ?>
                        <?php echo JSSTformfield::hidden('action', 'reply_savereply'); ?>
                        <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                        <?php echo JSSTformfield::hidden('jsstpageid', get_the_ID()); ?>
                    </form>
                <?php } ?>
            </div>   
            <?php
        } else { // Record Not FOund
            JSSTlayout::getNoRecordFound();
        }
    } else {// User is guest
        JSSTlayout::getUserGuest();
    }
} else { // System is offline
    JSSTlayout::getSystemOffline();
}
?>
