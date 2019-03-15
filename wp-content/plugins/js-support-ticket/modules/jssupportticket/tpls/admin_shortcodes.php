<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">	
    <span class="js-adminhead-title"><a class="jsanchor-backlink" href="<?php echo admin_url('admin.php?page=jssupportticket&jstlay=controlpanel');?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/back-icon.png" /></a><span class="jsheadtext"><?php echo __('Short Codes','js-support-ticket'); ?></span>
		</span>

		<div id="jsst-shortcode-wrapper">
		    <div class="jsst-shortcode-1"><?php echo __('JS Support Ticket Control Panel', 'js-support-ticket'); ?></div>
		    <div class="jsst-shortcode-2"><?php echo "[jssupportticket]"; ?></div>
		    <div class="jsst-shortcode-3"><?php echo __("JS Support Ticket main control panel", 'js-support-ticket'); ?></div>
		</div>
		<div id="jsst-shortcode-wrapper">
		    <div class="jsst-shortcode-1"><?php echo __('Add Ticket', 'js-support-ticket'); ?></div>
		    <div class="jsst-shortcode-2"><?php echo "[jssupportticket_addticket]"; ?></div>
		    <div class="jsst-shortcode-3"><?php echo __("Add new ticket form for both user and staff", 'js-support-ticket'); ?></div>
		</div>
		<div id="jsst-shortcode-wrapper">
		    <div class="jsst-shortcode-1"><?php echo __('My Tickets', 'js-support-ticket'); ?></div>
		    <div class="jsst-shortcode-2"><?php echo "[jssupportticket_mytickets]"; ?></div>
		    <div class="jsst-shortcode-3"><?php echo __("My tickets for both user and staff", 'js-support-ticket'); ?></div>
		</div>
	</div>
</div>
