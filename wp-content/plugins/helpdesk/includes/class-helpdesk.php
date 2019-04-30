<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 * @author     Your Name <email@example.com>
 */
class Helpdesk
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Helpdesk_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'helpdesk';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Helpdesk_Loader. Orchestrates the hooks of the plugin.
     * - Helpdesk_i18n. Defines internationalization functionality.
     * - Helpdesk_Admin. Defines all hooks for the admin area.
     * - Helpdesk_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-helpdesk-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-helpdesk-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-helpdesk-admin.php';

        /**
         * The class responsible for defining all settings.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-helpdesk-settings.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-helpdesk-settings-api.php';

        /**
         * The class responsible for defining all settings callbacks.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-helpdesk-settings-callbacks.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-helpdesk-public.php';

        $this->loader = new Helpdesk_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Helpdesk_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Helpdesk_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Helpdesk_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        
        $this->loader->add_action('wp_ajax_test_ajax', $plugin_admin, 'test_ajax');

        $plugin_settings = new Helpdesk_Settings(new Helpdesk_Settings_Api(), new Helpdesk_Settings_Callbacks());

        /**
         * register our settings_init to the admin_init action hook
         */
        $this->loader->add_action('admin_init', $plugin_settings, 'settings_init');

        /**
         * register our options_page to the admin_menu action hook
         */
        $this->loader->add_action('admin_menu', $plugin_settings, 'options_page');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Helpdesk_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_ajax_follow_thread', $plugin_public, 'wphd_follow_ticket');
        $this->loader->add_action('wp_ajax_nopriv_follow_thread', $plugin_public, 'wphd_follow_ticket');

        $this->loader->add_action('wp_ajax_edit_timeoff', $plugin_public, 'wphd_edit_timeoff');
        $this->loader->add_action('wp_ajax_nopriv_edit_timeoff', $plugin_public, 'wphd_edit_timeoff');


        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'register_book_type');
        $this->loader->add_shortcode('all-tickets', $plugin_public, 'wphd_sc_all_tickets');
        $this->loader->add_shortcode('followed-tickets', $plugin_public, 'wphd_sc_followed_tickets');
        $this->loader->add_filter('plugins_loaded', $plugin_public, 'wphd_add_custom_fields');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Helpdesk_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}
