<?php

/**
 * @package Helpdesk Plugin
 */

namespace Inc\Pages;

use Inc\Base\BaseController;
use Inc\API\SettingsAPI;

class Admin extends BaseController
{
    public $settings;
    public $pages = array();

    public function __construct()
    {
        $this->settings = new SettingsAPI();

        // Template for administration pages.
        $this->pages = [
            [
                'page_title'=>"Helpdesk Page",
                'menu_title'=>"Helpdesk",
                'capability'=>"manage_options",
                'menu_slug'=>"helpdesk",
                'callback'=> function () { echo "<h1>Helpdesk Settings</h1>"; },
                'icon_url'=>'dashicons-desktop',
                'position'=>110
            ],
        ];
    }

    /**
	 * Register plugin with admin menu.
	 */
	public function register()
	{

        $this->settings->add_pages($this->pages)->register();
	}

	/**
	 * Load admin index page.
	 */
	function admin_index()
	{
		require_once $this->plugin_path . 'templates/admin.php';
	}
}