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
    public $sub_pages = array();

    public function __construct()
    {
        parent::__construct();

        $this->settings = new SettingsAPI();

        // Template for administration pages.
        $this->pages = [
            [
                'page_title'=>"Helpdesk Settings",
                'menu_title'=>"Helpdesk Settings",
                'capability'=>"manage_options",
                'menu_slug'=>"helpdesk",
                'callback'=> function () { echo "<h1>Helpdesk Settings</h1>"; },
                'icon_url'=>'dashicons-desktop',
                'position'=>110
            ],
        ];

        $this->sub_pages = [
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Problem Manager',
                'menu_title'=>'Problem Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_pm',
                'callback'=> function () { echo "<h1>Problem Manager</h1>"; },
            ],
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Equipment Manager',
                'menu_title'=>'Equipment Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_em',
                'callback'=> function () { echo "<h1>Equipment Manager</h1>"; },
            ],
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Software Manager',
                'menu_title'=>'Software Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_sm',
                'callback'=> function () { echo "<h1>Software Manager</h1>"; },
            ],
        ];
    }

    /**
	 * Register plugin with admin menu.
	 */
	public function register()
	{

        $this->settings->add_pages($this->pages)->with_sub_page('Dashboard')->add_sub_pages($this->sub_pages)->register();
	}

//	/**
//	 * Load admin index page.
//	 */
//	function admin_index()
//	{
//		require_once $this->plugin_path . 'templates/admin.php';
//	}
}