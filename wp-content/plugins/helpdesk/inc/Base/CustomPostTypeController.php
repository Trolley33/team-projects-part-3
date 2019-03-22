<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc\Base;

use Inc\API\SettingsAPI;
use Inc\API\Callbacks\AdminCallbacks;

class CustomPostTypeController extends BaseController
{
    public $sub_pages = array();

    public $callbacks;

    /**
     * Register plugin with admin menu.
     */
    public function register()
    {
        $this->settings = new SettingsAPI();

        $this->callbacks = new AdminCallbacks();

        add_action('init', array($this, "register_custom_post_types"));
    }

    public function register_custom_post_types()
    {
        
    }

}