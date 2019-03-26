<?php

/**
 * @package Helpdesk Plugin
 */

namespace Inc\Pages;

use Inc\API\SettingsAPI;
use Inc\Base\BaseController;
use Inc\API\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
    public $settings;
    public $pages = array();
    public $sub_pages = array();

    public $callbacks;


    /**
	 * Register plugin with admin menu.
	 */
	public function register()
	{
        $this->settings = new SettingsAPI();
        $this->callbacks = new AdminCallbacks();

        $this->make_pages();

        $this->make_sub_pages();

        $this->settings->add_pages($this->pages)->with_sub_page('Dashboard')->add_sub_pages($this->sub_pages)->register();
	}

	public function make_pages()
    {
        // Template for administration pages.
        $this->pages = [
            [
                'page_title'=>"Helpdesk Settings",
                'menu_title'=>"Helpdesk Settings",
                'capability'=>"manage_options",
                'menu_slug'=>"helpdesk",
                'callback'=> array($this->callbacks, 'dashboard'),
                'icon_url'=>'dashicons-desktop',
                'position'=>110
            ],
        ];
    }

    public function make_sub_pages()
    {
        $this->sub_pages = [
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Skill Manager',
                'menu_title'=>'Skill Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_skm',
                'callback'=> array($this->callbacks, 'skill_manager'),
            ],
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Equipment Manager',
                'menu_title'=>'Equipment Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_em',
                'callback'=> array($this->callbacks, 'equipment_manager'),
            ],
            [
                'parent_slug'=>'helpdesk',
                'page_title'=>'Software Manager',
                'menu_title'=>'Software Manager',
                'capability'=>'manage_options',
                'menu_slug'=>'helpdesk_sm',
                'callback'=> array($this->callbacks, 'software_manager'),
            ],
        ];
    }
}