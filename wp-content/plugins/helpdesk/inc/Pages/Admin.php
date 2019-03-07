<?php

/**
 * @package Helpdesk Plugin
 */

namespace Inc\Pages;

class Admin
{	
	/**
	 * Register plugin with admin menu.
	 */
	public function register()
	{
		add_action('admin_menu', array($this, 'add_admin_pages'));
	}

	/**
	 * Add icon to admin menu.
	 */
	function add_admin_pages ()
	{
		add_menu_page( "Helpdesk Page", "Helpdesk", 'manage_options', "helpdesk", array($this, 'admin_index'), 'dashicons-desktop', 110 );
	}

	/**
	 * Load admin index page.
	 */
	function admin_index()
	{
		require_once PLUGIN_PATH . 'templates/admin.php';
	}
}