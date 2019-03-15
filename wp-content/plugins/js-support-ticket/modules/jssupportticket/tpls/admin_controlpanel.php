<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script>
            google.setOnLoadCallback(drawStackChartHorizontal);
            function drawStackChartHorizontal() {
            var data = google.visualization.arrayToDataTable([
                <?php
                echo jssupportticket::$_data['stack_chart_horizontal']['title'] . ',';
                echo jssupportticket::$_data['stack_chart_horizontal']['data'];
                ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    var options = {
                    height:250,
                            legend: { position: 'top', maxLines: 3 },
                            bar: { groupWidth: '75%' },
                            isStacked: true,
                            colors:<?php echo jssupportticket::$_data['stack_chart_horizontal']['colors']; ?>,
                    };
                    var chart = new google.visualization.BarChart(document.getElementById("stack_chart_horizontal"));
                    chart.draw(view, options);
            }
</script>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">   
        <div id="js-main-cp-wrapper">
        <div id="js-main-head-cp">
            <div class="js-cptext"><?php echo __('Dashboard', 'js-support-ticket'); ?></div>
            <div class="js-cpmenu">
                <span class="dashboard-icon">
                    <?php
                        $url = 'https://www.joomsky.com/appsys/latestversion.php?prod=wp-support-ticket';
                        $body_array = array();
                        $body_array['dt'] = date('Y-m-d');
                        $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
                        if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                            $result = (int) json_decode($response['body']);
                        }else{
                             if(!is_wp_error($response)){
                                $error = $response['response']['message'];
                            }else{
                                $error = $response->get_error_message();
                            }
                            echo $error;
                            $result = '';
                        }
                        $current_version = (int) str_replace('.', '', jssupportticket::$_data['version']);
                        if  ($result != '') {
                            if($result <=  $current_version){ 
                                $image = jssupportticket::$_pluginpath.'includes/images/admincp/up-dated.png';
                                $lang = __('Your System Is Up To Date','js-support-ticket');
                                $class = "green";
                            }else{ 
                                $image = jssupportticket::$_pluginpath.'includes/images/admincp/new-version.png';
                                $lang = __('New Version Is Available','js-support-ticket');
                                $class = "orange";
                            }
                        }else{
                            $image = jssupportticket::$_pluginpath.'includes/images/admincp/connection-error.png';
                            $lang = __('Unable Connect To Server','js-support-ticket');
                            $class = "red";
                        } ?>
                    
                    <span class="download <?php echo $class; ?>">
                        <img src="<?php echo esc_url($image); ?>" />
                        <span><?php echo $lang; ?></span>
                    </span>
                </span>
            </div>
        </div>

        <div id="js-total-count-cp">
            <div class="js-total-count">
                <img class="img" src="<?php echo jssupportticket::$_pluginpath; ?>/includes/images/admincp/new.png" />
                <div class="data">
                    <span class="jstotal"><?php echo jssupportticket::$_data['ticket_total']['openticket']; ?></span>
                    <span class="jsstatus"><?php echo __('New','js-support-ticket'); ?></span>
                </div>
            </div>
            <div class="js-total-count">
                <img class="img" src="<?php echo jssupportticket::$_pluginpath; ?>/includes/images/admincp/answered.png" />
                <div class="data">
                    <span class="jstotal"><?php echo jssupportticket::$_data['ticket_total']['answeredticket']; ?></span>
                    <span class="jsstatus"><?php echo __('Answered','js-support-ticket'); ?></span>
                </div>
            </div>
            <div class="js-total-count">
                <img class="img" src="<?php echo jssupportticket::$_pluginpath; ?>/includes/images/admincp/pending.png" />
                <div class="data">
                    <span class="jstotal"><?php echo jssupportticket::$_data['ticket_total']['pendingticket']; ?></span>
                    <span class="jsstatus"><?php echo __('Pending','js-support-ticket'); ?></span>
                </div>
            </div>
        </div>

        <div id="js-pm-graphtitle">
            <img class="js-giamge" src="<?php echo jssupportticket::$_pluginpath; ?>/includes/images/admincp/menu-icon.png" />
            <?php echo __('Statistics', 'js-support-ticket'); ?>
            <small> <?php $curdate = date_i18n('Y-m-d'); $fromdate = date_i18n('Y-m-d', strtotime("now -1 month")); echo " ($fromdate - $curdate)"; ?> </small>
        </div>
        <div id="js-pm-grapharea">
            <div id="stack_chart_horizontal" style="width:100%;"></div>
        </div>

        <span class="js-admin-menus-head"><?php echo __('Admin', 'js-support-ticket'); ?></span>
        <div id="js-wrapper-menus">
            <a class="js-admin-menu-link" href="?page=ticket&jstlay=tickets"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/ticket.png"/><div class="jsmenu-text"><?php echo __('Tickets', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=department&jstlay=departments"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/departments.png"/><div class="jsmenu-text"><?php echo __('Departments', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=priority&jstlay=priorities"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/priority.png"/><div class="jsmenu-text"><?php echo __('Priorities', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=email&jstlay=emails"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/system-email.png"/><div class="jsmenu-text"><?php echo __('System Emails', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=fieldordering&jstlay=fieldordering&fieldfor=1"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/fieldordering.png"/><div class="jsmenu-text"><?php echo __('Fields', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=systemerror&jstlay=systemerrors"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/system_error.png"/><div class="jsmenu-text"><?php echo __('System Errors', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=emailtemplate&jstlay=emailtemplates"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/email_template.png"/><div class="jsmenu-text"><?php echo __('Email Templates', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=reports&jstlay=overallreport"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/report_icon.png"/><div class="jsmenu-text"><?php echo __('Reports', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=jssupportticket&jstlay=aboutus"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/about_us.png"/><div class="jsmenu-text"><?php echo __('About Us','js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=jssupportticket&jstlay=translations"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/language-icon.png"/><div class="jsmenu-text"><?php echo __('Translations','js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=jssupportticket&jstlay=propage"><img class="jsmenu-img" style="height:60px;width:auto;" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/pro_icon.png"/><div class="jsmenu-text"><?php echo __('Pro Features', 'js-support-ticket'); ?></div></a>
        </div>
        <div id="wp-jobs-banner">
            <a href="https://www.joomsky.com/products/js-supprot-ticket-pro-wp.html" target="_blank">
                <img src="<?php echo jssupportticket::$_pluginpath."/includes/images/tickets-pro-banner.png"; ?>" />
            </a>
        </div>

        <span class="js-admin-menus-head"><?php echo __('Configuration', 'js-support-ticket'); ?></span>
        <div id="js-wrapper-menus">
            <a class="js-admin-menu-link" href="?page=configuration&jstlay=configurations"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/configuration.png"/><div class="jsmenu-text"><?php echo __('Configurations', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" href="?page=proinstaller&jstlay=step1"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/update.png"/><div class="jsmenu-text"><?php echo __('Upgrade', 'js-support-ticket'); ?></div></a>
        </div>
        <?php
        $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
        ?>
        <div id="js-pm-graphtitle" class="tickettitle"> <img class="js-giamge" src="<?php echo jssupportticket::$_pluginpath; ?>/includes/images/admincp/menu-icon.png" />
            <?php echo __('Latest Tickets', 'js-support-ticket'); ?>
        </div>
        <div class="js-ticket-admin-cp-tickets">
            <div class="js-row js-ticket-admin-cp-head js-ticket-admin-hide-head">
                <div class="js-col-xs-12 js-col-md-2"><?php echo __('Ticket ID', 'js-support-ticket'); ?></div>
                <div class="js-col-xs-12 js-col-md-3"><?php echo __($field_array['subject'], 'js-support-ticket'); ?></div>
                <div class="js-col-xs-12 js-col-md-1"><?php echo __($field_array['status'], 'js-support-ticket'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo __('From', 'js-support-ticket'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo __($field_array['priority'], 'js-support-ticket'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo __('Created', 'js-support-ticket'); ?></div>
            </div>
            <?php foreach (jssupportticket::$_data['tickets'] AS $ticket): ?>
                <div class="js-ticket-admin-cp-data">
                    <div class="js-col-xs-12 js-col-md-2"><span class="js-ticket-admin-cp-showhide"><?php
                            echo __('Ticket ID', 'js-support-ticket');
                            echo " : ";
                            ?></span> <a href="<?php echo admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $ticket->id); ?>"><?php echo $ticket->ticketid; ?></a></div>
                    <div class="js-col-xs-12 js-col-md-3 js-admin-cp-text-elipses"><span class="js-ticket-admin-cp-showhide" ><?php
                            echo __('Subject', 'js-support-ticket');
                            echo " : ";
                            ?></span> <?php echo $ticket->subject; ?></div>
                    <div class="js-col-xs-12 js-col-md-1">
                        <span class="js-ticket-admin-cp-showhide" ><?php
                        echo __('Status', 'js-support-ticket');
                        echo " : ";
                        ?></span>
                        <?php
                        if ($ticket->status == 0) {
                            $style = "red;";
                            $status = __('New', 'js-support-ticket');
                        } elseif ($ticket->status == 1) {
                            $style = "orange;";
                            $status = __('Waiting Staff Reply', 'js-support-ticket');
                        } elseif ($ticket->status == 2) {
                            $style = "#FF7F50;";
                            $status = __('In Progress', 'js-support-ticket');
                        } elseif ($ticket->status == 3) {
                            $style = "green;";
                            $status = __('Waiting Your Reply', 'js-support-ticket');
                        } elseif ($ticket->status == 4) {
                            $style = "blue;";
                            $status = __('Closed', 'js-support-ticket');
                        }
                        echo '<span style="color:' . $style . '">' . $status . '</span>';
                        ?>
                    </div>
                    <div class="js-col-xs-12 js-col-md-2"> <span class="js-ticket-admin-cp-showhide" ><?php
                            echo __('From', 'js-support-ticket');
                            echo " : ";
                            ?></span> <?php echo $ticket->name; ?></div>
                    <div class="js-col-xs-12 js-col-md-2" style="color:<?php echo $ticket->prioritycolour; ?>;"> <span class="js-ticket-admin-cp-showhide" ><?php
                            echo __('Priority', 'js-support-ticket');
                            echo " : ";
                            ?></span> <?php echo __($ticket->priority, 'js-support-ticket'); ?></div>
                    <div class="js-col-xs-12 js-col-md-2"><span class="js-ticket-admin-cp-showhide" ><?php
            echo __('Created', 'js-support-ticket');
            echo " : ";
            ?></span> <?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?></div>
                </div>
        <?php endforeach; ?>
        </div>
        <span class="js-admin-menus-head"><?php echo __('Support Area', 'js-support-ticket'); ?></span>
        <div id="js-wrapper-menus">
            <a class="js-admin-menu-link" href="?page=jssupportticket&jstlay=shortcodes"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/short_code.png"/><div class="jsmenu-text"><?php echo __('Short Codes', 'js-support-ticket'); ?></div></a>

            <a class="js-admin-menu-link" target="_blank" href="https://www.joomsky.com/appsys/documentations/wp-support-ticket"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/documentation.png"/><div class="jsmenu-text"><?php echo __('Documentation', 'js-support-ticket'); ?></div></a>
            <a class="js-admin-menu-link" target="_blank" href="https://www.joomsky.com/appsys/forum/wp-support-ticket"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/forum.png"/><div class="jsmenu-text"><?php echo __('Forum', 'js-support-ticket'); ?></div></a>
            <?php /* <a class="js-admin-menu-link" target="_blank" href="https://www.joomsky.com/appsys/support/wp-support-ticket"><img class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/support.png"/><div class="jsmenu-text"><?php echo __('Support', 'js-support-ticket'); ?></div></a> */ ?>

        <a class="banner" target="_blank" href="http://demo.joomsky.com/js-jobs/wp/pro"><img class="banner" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/new-banner-2.png"/></a>
        </div>
        <div id="jsreview-banner">
            <div class="review">
                <div class="upper">
                    <div class="imgs">
                        <img class="reviewpic" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/review.png">
                        <img class="reviewpic2" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/corner-1.png">
                    </div>
                    <div class="text">
                        <div class="simple-text">
                            <span class="nobold"><?php echo __('We\'d love to hear from ', 'js-support-ticket'); ?></span>
                            <span class="bold"><?php echo __('You', 'js-support-ticket'); ?>.</span>
                            <span class="nobold"><?php echo __('Please write appreciated review at', 'js-support-ticket'); ?></span>
                        </div>
                        <a href="https://wordpress.org/support/plugin/js-support-ticket/reviews" target="_blank"><?php echo __('Word Press Extension Directory', 'js-support-ticket'); ?><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/arrow2.png"></a>
                    </div>
                    <div class="right">
                        <img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/star.png">
                    </div>
                </div>
                <div class="lower">

                </div>
            </div>

        </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("span.dashboard-icon").find('span.download').hover(function(){
                    jQuery(this).find('span').toggle("slide");
                    }, function(){
                    jQuery(this).find('span').toggle("slide");
                });
            });
        </script>
    </div>
</div>
