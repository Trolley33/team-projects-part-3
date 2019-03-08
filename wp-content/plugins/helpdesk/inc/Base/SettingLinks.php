<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc\Base;

class SettingLinks
{
	public function register()
	{
		// Do something when plugin is activated.
        add_filter("plugin_action_links_".PLUGIN, array($this, 'settings_link'));
	}

	public function settings_link( $links )
	{
		$settings_link = "<a href='admin.php?page=helpdesk'>Settings</a>";
		array_push($links, $settings_link);
		return $links;
	}
}