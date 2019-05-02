<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/admin
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Helpdesk_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Helpdesk_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('hd_bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css");
        wp_enqueue_style("hd_datatables", '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/helpdesk-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Helpdesk_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Helpdesk_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script('hd_bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js");
        wp_enqueue_script("hd_datatables", '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/helpdesk-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'followed_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
        wp_enqueue_script( 'chart.js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js');
    }

    public function display_admin_settings()
    {
        $settings = new Helpdesk_Settings_Callbacks();

        // register a new setting for "wporg" page
        register_setting('wporg', 'wporg_options');

        // register a new section in the "wporg" page
        add_settings_section(
            'wporg_section_developers',
            __('The Matrix has you.', 'wporg'),
            'wporg_section_developers_cb',
            'wporg'
        );

        // register a new field in the "wporg_section_developers" section, inside the "wporg" page
        add_settings_field(
            'wporg_field_pill', // as of WP 4.6 this value is used only internally
            // use $args' label_for to populate the id inside the callback
            __('Pill', 'wporg'),
            'wporg_field_pill_cb',
            'wporg',
            'wporg_section_developers',
            [
                'label_for' => 'wporg_field_pill',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
            ]
        );

        // register a new section in the "reading" page
        add_settings_section(
            'wporg_settings_section',
            'WPOrg Settings Section',
            array($settings, 'section'),
            'WPOrg'
        );

        // register a new field in the "wporg_settings_section" section, inside the "reading" page
        add_settings_field(
            'wporg_settings_field',
            'WPOrg Setting',
            array($settings, 'field'),
            'WPOrg',
            'wporg_settings_section'
        );
    }

    public function get_tickets_past_year() {
        global $wpdb;
        // SELECT DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY)
        // SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' AND post_date >= DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY) ORDER BY `wp_posts`.`post_date`
        // SELECT DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH)
        // SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH) ORDER BY `wp_posts`.`post_date`
        // SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH) AND post_date < DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY) ORDER BY `wp_posts`.`post_date`
        $col = $wpdb->get_col("SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH) AND post_date < DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY) ORDER BY `wp_posts`.`post_date`");
        echo json_encode($col);
        die();
    }
}
