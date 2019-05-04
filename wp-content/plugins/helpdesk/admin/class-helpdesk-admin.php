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
        // wp_enqueue_style('hd_bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css");
        wp_enqueue_style('google_fonts', "https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons");
        wp_enqueue_style('bootstrap_material', "https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css");
        wp_enqueue_style("hd_datatables", '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
        wp_enqueue_style("daterangepicker", 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');
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

        wp_enqueue_script('popper', "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js");
        wp_enqueue_script('bootstrap_material', "https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js");
        // wp_enqueue_script('hd_bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js");
        wp_enqueue_script("hd_datatables", '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/helpdesk-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'followed_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
        wp_enqueue_script('chart.js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js');
        wp_enqueue_script('moment.js', 'https://momentjs.com/downloads/moment-with-locales.min.js');
        wp_enqueue_script('daterangepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js');
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

    public function get_tickets_past_year()
    {
        global $wpdb;
        $col = $wpdb->get_col("SELECT post_date FROM `wp_posts` 
                WHERE post_type = 'ticket' 
                AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH) 
                AND post_date < DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY) 
                ORDER BY `wp_posts`.`post_date`;
        ");
        echo json_encode($col);
        die();
    }

    public function get_problem_hardware_past_year()
    {
        global $wpdb;
        // TODO: REFACTOR.
        $time_stamps = $wpdb->get_results("SELECT wp_terms.name FROM `wp_posts` 
                JOIN `wp_term_relationships` 
                ON wp_posts.ID = wp_term_relationships.object_id 
                JOIN wp_term_taxonomy  
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
                JOIN `wp_terms`
                ON wp_terms.term_id = wp_term_taxonomy.term_id
                WHERE post_type = 'ticket' 
                AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH)
                AND wp_term_taxonomy.taxonomy = 'hardware'");
        echo json_encode($time_stamps);
        die();
    }


    public function get_problem_software_past_year() {
        global $wpdb;
        // TODO: REFACTOR.
        $time_stamps = $wpdb->get_results("
                SELECT wp_terms.name FROM `wp_posts` 
                JOIN `wp_term_relationships` 
                ON wp_posts.ID = wp_term_relationships.object_id 
                JOIN wp_term_taxonomy  
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
                JOIN `wp_terms`
                ON wp_terms.term_id = wp_term_taxonomy.term_id
                WHERE post_type = 'ticket' 
                AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH) 
                AND post_date < DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY) 
                AND wp_term_taxonomy.taxonomy = 'software';
        ");
        echo json_encode($time_stamps);
        die();
    }
    
    public function get_tickets_past_month() {

        global $wpdb;
        $col = $wpdb->get_col("SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' AND post_date >= DATE_ADD(CURDATE(), INTERVAL -1 MONTH) AND post_date < CURDATE() ORDER BY `wp_posts`.`post_date`");
        echo json_encode($col);
        die();
    }

    public function get_tickets()
    {
        global $wpdb;
        $col = $wpdb->get_col("SELECT post_date FROM `wp_posts` WHERE post_type = 'ticket' ORDER BY `wp_posts`.`post_date`");
        echo json_encode($col);
        die();
    }


    public function get_agent_analytics() {
        if (!isset($_REQUEST['id'])) {
            exit(1);
        }
        global $wpdb;


        $id = $wpdb->_escape($_REQUEST['id']);
        /*
        Want to get:
        - Number of tickets assigned in time frame
        - Number of tickets closed in time frame.
        - Avg length of time tickets open.
        - ?
        */
        $open_query = "
            SELECT COUNT(*) FROM wp_postmeta a
            JOIN wp_postmeta b
              ON a.post_id = b.post_id
            WHERE 
              a.meta_key = '_wpas_assignee'
              AND a.meta_value = '$id'
              AND b.meta_key = '_wpas_status'
              AND b.meta_value = 'open';
        ";

        $closed_query = "
            SELECT c.meta_value FROM wp_postmeta a
            JOIN wp_postmeta b
              ON a.post_id = b.post_id
            JOIN wp_postmeta c
              ON b.post_id = c.post_id
            WHERE 
              a.meta_key = '_wpas_assignee'
              AND a.meta_value = '$id'
              AND b.meta_key = '_wpas_status'
              AND b.meta_value = 'closed'
              AND c.meta_key = '_ticket_closed_on';
        ";

        $open_column = $wpdb->get_col($open_query)[0];
        $closed_column = $wpdb->get_col($closed_query);

        $output = new stdClass();
        $output->open_tickets = intval($open_column);
        $output->closed_tickets = $closed_column;
        echo json_encode($output);
        die();
    }


    public function get_tickets_full() {
        global $wpdb;
        $time_stamps = $wpdb->get_results("SELECT * FROM `wp_posts` 
                JOIN `wp_term_relationships` 
                ON wp_posts.ID = wp_term_relationships.object_id 
                JOIN wp_term_taxonomy  
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
                JOIN `wp_terms`
                ON wp_terms.term_id = wp_term_taxonomy.term_id
                WHERE post_type = 'ticket' 
                AND post_date >= DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 1-DAY(CURDATE()) DAY), INTERVAL -12 MONTH)
                AND wp_term_taxonomy.taxonomy = 'hardware'");
        echo json_encode($time_stamps);
        die();
    }
}
