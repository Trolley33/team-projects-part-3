<?php

/**
 * The admin settings page methods
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/settings
 */

/**
 * The admin settings page methods.
 *
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/settings
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Settings
{
    private $settings_api;
    private $callbacks;

    public $pages = array();
    public $sub_pages = array();

    public function __construct(Helpdesk_Settings_Api $settings_api, Helpdesk_Settings_Callbacks $callbacks)
    {
        $this->settings_api = $settings_api;
        $this->callbacks = $callbacks;
    }

    /**
     * @internal never define functions inside callbacks.
     * these functions could be run multiple times; this would result in a fatal error.
     */

    /**
     * custom option and settings
     */
    public function settings_init()
    {
        // register a new setting for "helpdesk" page
        register_setting('helpdesk_dashboard', 'helpdesk_options');

        add_settings_section('helpdesk_section_dashboard', __('The Matrix has you.', 'helpdesk'), [$this->callbacks, 'section_developers'], 'helpdesk_dashboard');

        add_settings_field(
            'helpdesk_field_pill',
            // use $args' label_for to populate the id inside the callback
            __('Pill', 'helpdesk'),
            [$this->callbacks, 'field_pill'],
            'helpdesk_dashboard',
            'helpdesk_section_dashboard',
            [
                'label_for' => 'helpdesk_field_pill',
                'class' => 'helpdesk_row',
                'helpdesk_custom_data' => 'custom',
            ]
        );
    }

    /**
     * top level menu
     */
    public function options_page()
    {
        $this->make_pages();

        $this->make_sub_pages();

        $this->settings_api->add_pages($this->pages)->with_sub_page('Dashboard')->add_sub_pages($this->sub_pages)->add_admin_menu();
    }

    public function make_pages()
    {
        // Template for administration pages.
        $this->pages = [
            [
                'page_title' => "Helpdesk Settings",
                'menu_title' => "Helpdesk Settings",
                'capability' => "manage_options",
                'menu_slug' => "helpdesk_dashboard",
                'callback' => array($this->callbacks, 'dashboard'),
                'icon_url' => 'dashicons-desktop',
                'position' => 110,
            ],
        ];
    }

    public function make_sub_pages()
    {
        $this->sub_pages = [
            [
                'parent_slug' => 'helpdesk_dashboard',
                'page_title' => 'Skill Manager',
                'menu_title' => 'Skill Manager',
                'capability' => 'manage_options',
                'menu_slug' => 'helpdesk_skm',
                'callback' => array($this->callbacks, 'skill_manager'),
            ],
            [
                'parent_slug' => 'helpdesk_dashboard',
                'page_title' => 'Equipment Manager',
                'menu_title' => 'Equipment Manager',
                'capability' => 'manage_options',
                'menu_slug' => 'helpdesk_em',
                'callback' => array($this->callbacks, 'equipment_manager'),
            ],
            [
                'parent_slug' => 'helpdesk_dashboard',
                'page_title' => 'Software Manager',
                'menu_title' => 'Software Manager',
                'capability' => 'manage_options',
                'menu_slug' => 'helpdesk_sm',
                'callback' => array($this->callbacks, 'software_manager'),
            ],
        ];
    }
}
