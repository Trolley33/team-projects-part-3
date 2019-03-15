<?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
<?php
JSSTmessage::getMessage();
if (jssupportticket::$_config['offline'] == 2) {
    if (get_current_user_id() != 0|| jssupportticket::$_config['visitor_can_create_ticket'] == 1) {
        ?>
        <!-- Dashboard Links -->
        <div id="js-dash-menu-link-wrp">
            <div class="js-section-heading"><?php echo __('Dashboard','js-support-ticket'); ?></div>
            <div class="js-menu-links-wrp">
                <?php if (jssupportticket::$_config['cplink_openticket_user'] == 1): ?>
                    <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'addticket'))); ?>">
                        <div class="js-ticket-dash-menu-icon">
                            <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/add-ticket.png'; ?>" />
                        </div>
                        <span class="js-ticket-dash-menu-text"><?php echo __('New Ticket', 'js-support-ticket'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if (jssupportticket::$_config['cplink_myticket_user'] == 1): ?>
                    <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'myticket'))); ?>">
                        <div class="js-ticket-dash-menu-icon">
                            <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/my-tickets.png'; ?>" />
                        </div>
                        <span class="js-ticket-dash-menu-text"><?php echo __('My Tickets', 'js-support-ticket'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if (jssupportticket::$_config['cplink_checkticketstatus_user'] == 1): ?>
                    <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketstatus'))); ?>">
                        <div class="js-ticket-dash-menu-icon">
                            <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/ticket-status.png'; ?>" />
                        </div>
                        <span class="js-ticket-dash-menu-text"><?php echo __('Ticket Status', 'js-support-ticket'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if (jssupportticket::$_config['cplink_login_logout_user'] == 1){ ?>
                    <?php if (!is_user_logged_in()): ?>
                        <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'login'))); ?>">
                            <div class="js-ticket-dash-menu-icon">
                                <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/login.png'; ?>" />
                            </div>
                            <span class="js-ticket-dash-menu-text"><?php echo __('Log in ', 'js-support-ticket'); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (jssupportticket::$_config['cplink_register_user'] == 1){ ?>
                        <?php if (!is_user_logged_in()):
                                $is_enable = get_option('users_can_register');// check to make sure user registration is enabled
                                if ($is_enable) { ?> <!-- only show the registration form if allowed -->
                                    <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'userregister'))); ?>">
                                        <div class="js-ticket-dash-menu-icon">
                                            <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/userregister.png'; ?>" />
                                        </div>
                                        <span class="js-ticket-dash-menu-text"><?php echo __('Register ', 'js-support-ticket'); ?></span>
                                    </a>
                                <?php } ?>
                        <?php endif; ?>
                    <?php } ?>
                    <?php if (is_user_logged_in()): ?>
                            <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="<?php echo wp_logout_url( home_url() ); ?>">
                                <div class="js-ticket-dash-menu-icon">
                                    <img class="js-ticket-dash-menu-img" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/logout.png'; ?>" />
                                </div>
                                <span class="js-ticket-dash-menu-text"><?php echo __('Log Out ', 'js-support-ticket'); ?></span>
                            </a>
                        <?php endif; ?>
                <?php } ?>
            </div>
        </div>
        <?php
    } else {// User is guest
        JSSTlayout::getUserGuest();
    }
} else { // System is offline
    JSSTlayout::getSystemOffline();
}
?>
