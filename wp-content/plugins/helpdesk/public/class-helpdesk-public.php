<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/public
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/helpdesk-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/helpdesk-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'followed_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));

    }

    public function register_book_type()
    {
        /* Registering post types code.
        register_post_type('wporg_product',
            array(
                'labels' => array(
                    'name' => __('Products'),
                    'singular_name' => __('Product'),
                ),
                'public' => true,
                'has_archive' => true,
            )
        );
        */
    }

    /**
     * All tickets page shortcode.
     */
    public function wphd_sc_all_tickets()
    {
		
        global $wpas_tickets, $post;

        $wpas_tickets = $this->wphd_get_all_tickets_for_shortcode();

        /* Get the ticket content */
        ob_start();

        /**
         * wpas_frontend_plugin_page_top is executed at the top
         * of every plugin page on the front end.
         */
        do_action('wpas_frontend_plugin_page_top', $post->ID, $post);

        /**
         * wpas_before_tickets_list hook
         */
        do_action('wpas_before_tickets_list');

        /**
         * Get the custom template.
         */
        wpas_get_template('list');

        /**
         * wpas_after_tickets_list hook
         */
        do_action('wpas_after_tickets_list');

        /**
         * Finally get the buffer content and return.
         *
         * @var string
         */
        $content = ob_get_clean();

        return $content;

    }

    /**
     * Get the list of tickets that should be shown in the [all-tickets] shortcode.
     *
     * @since 4.4.0
     *
     * @return WP_Query post array of tickets found
     */
    private function wphd_get_all_tickets_for_shortcode()
    {

        global $current_user, $post;

        /**
         * For some reason when the user ID is set to 0
         * the query returns posts whose author has ID 1.
         * In order to avoid that (for non logged users)
         * we set the user ID to -1 if it is 0.
         *
         * @var integer
         */
        $author = (0 !== $current_user->ID) ? $current_user->ID : -1;

        $args = array(
            'author' => 'any',
            'post_type' => 'ticket',
            'post_status' => 'any',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => -1,
            'no_found_rows' => false,
            'cache_results' => false,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        );

        //    /* Maybe only show open tickets */
        //    if ( true === boolval( wpas_get_option( 'hide_closed_fe', false) ) ) {
        //        $args_meta = array(
        //            'meta_query' => array(
        //                array(
        //                    'key'     => '_wpas_status',
        //                    'value'   => 'closed',
        //                    'compare' => '!=',
        //                ),
        //            ),
        //        ) ;
        //
        //        $args = array_merge($args, $args_meta);
        //    }

        $args = apply_filters('wpas_tickets_shortcode_query_args', $args);

        $wpas_tickets_found = new WP_Query($args);

        return $wpas_tickets_found;

    }

    /**
     * Followed tickets page shortcode.
     */
    function wphd_sc_followed_tickets() {

        global $wpas_tickets, $post;

        $wpas_tickets = $this->wphd_get_followed_tickets_for_shortcode() ;

        /* Get the ticket content */
        ob_start();

        /**
         * wpas_frontend_plugin_page_top is executed at the top
         * of every plugin page on the front end.
         */
        do_action( 'wpas_frontend_plugin_page_top', $post->ID, $post );

        /**
         * wpas_before_tickets_list hook
         */
        do_action( 'wpas_before_tickets_list' );

        /* If user is not logged in we display the register form */
        if ( !is_user_logged_in() ):

            $registration = wpas_get_option( 'login_page', false );

            if ( false !== $registration && !empty( $registration ) && !is_null( get_post( intval( $registration ) ) ) ) {
                /* As the headers are already sent we can't use wp_redirect. */
                echo '<meta http-equiv="refresh" content="0; url=' . get_permalink( $registration ) . '" />';
                wpas_get_notification_markup( 'info', __( 'You are being redirected...', 'awesome-support' ) );
                exit;
            }

            wpas_get_template( 'registration' );

        else:
            /**
             * Get the custom template.
             */
            wpas_get_template( 'list' );
        endif;

        /**
         * wpas_after_tickets_list hook
         */
        do_action( 'wpas_after_tickets_list' );

        /**
         * Finally get the buffer content and return.
         *
         * @var string
         */
        $content = ob_get_clean();

        return $content;

    }
    /**
     * Get the list of tickets that should be shown in the [tickets] shortcode.
     *
     * @since 4.4.0
     *
     * @param none
     *
     * @return array post array of tickets found
     */
    function wphd_get_followed_tickets_for_shortcode() {

        global $current_user, $post;

        /**
         * For some reason when the user ID is set to 0
         * the query returns posts whose author has ID 1.
         * In order to avoid that (for non logged users)
         * we set the user ID to -1 if it is 0.
         *
         * @var integer
         */
        $author = ( 0 !== $current_user->ID ) ? $current_user->ID : -1;

        $args = array(
            'post_type'              => 'ticket',
            'post_status'            => 'any',
            'order'                  => 'DESC',
            'orderby'                => 'date',
            'posts_per_page'         => - 1,
            'no_found_rows'          => false,
            'cache_results'          => false,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ) ;

        $args = apply_filters( 'wpas_tickets_shortcode_query_args', $args );

        $wpas_tickets_found = new WP_Query( $args );

        return $wpas_tickets_found;

    }

    public function wphd_add_custom_fields()
    {

        /*******************************************************************/
        /* Add Tag fields                                                  */
        /*******************************************************************/

        /** Get the labels for the ticket tags field if they are provided */
        $as_label_for_ticket_tag_singular 	= isset( $options[ 'label_for_ticket_tag_singular' ] ) ? $options[ 'label_for_ticket_tag_singular' ] : __( 'Tag', 'awesome-support' );
        $as_label_for_ticket_tag_plural 	= isset( $options[ 'label_for_ticket_tag_plural' ] ) ? $options[ 'label_for_ticket_tag_plural' ] : __( 'Tags', 'awesome-support' );

        /** Create the custom field for ticket tags */
        wpas_add_custom_field( 'ticket-tag', array(
            //'core'                  => true,
            'show_column'           => true,
            'log'                   => true,
            'field_type'            => 'taxonomy',
            'sortable_column'       => true,
            'taxo_std'              => false,
            'column_callback'       => 'wpas_show_taxonomy_column',
            'label'                 => $as_label_for_ticket_tag_singular,
            'name'                  => $as_label_for_ticket_tag_singular,
            'label_plural'          => $as_label_for_ticket_tag_plural,
            'taxo_hierarchical'     => false,
            'update_count_callback' => 'wpas_update_ticket_tag_terms_count',
            'select2'               => true,
            'taxo_manage_terms' 	=> 'ticket_manage_tags',
            'taxo_edit_terms'   	=> 'ticket_edit_tags',
            'taxo_delete_terms' 	=> 'ticket_delete_tags',
            'title'           		=> $as_label_for_ticket_tag_singular,
            'order' => '0'
        ) );

        $os_args = array(
            'name' => 'OS',
            'args' => array(
                'title' => __( 'Operating Systems', 'helpdesk' ),
                'label' => __( 'Operating System', 'helpdesk' ),
                'label_plural' => __( 'Operating Systems', 'helpdesk' ),
                'select2' => true,
                'order' => '1'
            )
        );
        wpas_add_custom_taxonomy($os_args['name'], $os_args['args']);


        $hw_args = array(
            'name' => 'hardware',
            'args' => array(
                'title' => __( 'Hardware', 'helpdesk' ),
                'label' => __( 'Affected Hardware', 'helpdesk' ),
                'label_plural' => __( 'Affected Hardware', 'helpdesk' ),
                'select2' => true,
                'order' => '2'
            )
        );
        wpas_add_custom_taxonomy($hw_args['name'], $hw_args['args']);

        $sw_args = array(
            'name' => 'software',
            'args' => array(
                'title' => __( 'Software', 'helpdesk' ),
                'label' => __( 'Affected Software', 'helpdesk' ),
                'label_plural' => __( 'Affected Software', 'helpdesk' ),
                'select2' => true,
                'order' => '3'
            )
        );


        wpas_add_custom_taxonomy($sw_args['name'], $sw_args['args']);
    }

    public function wphd_follow_ticket () {
        if (!isset($_REQUEST['pid']) || !isset($_REQUEST['uid']) || !isset($_REQUEST['follow']))
        {
            echo "Error!";
            return;
        }

        $pid = $_REQUEST['pid'];
        $uid = $_REQUEST['uid'];
        $follow = $_REQUEST['follow'];

        global $wpdb;
        if ($follow) {
            $query = "INSERT INTO wp_followed_tickets (postid, userid) VALUES ('$pid', '$uid')";
            $wpdb->query($query);
        }
        else {
            $query = "DELETE FROM wp_followed_tickets WHERE postid='$pid' AND userid='$uid'";
            $wpdb->query($query);
        }
    }

    public function wphd_edit_timeoff ()
    {
        if (!isset($_REQUEST['type'])) {
            echo "Missing edit type";
            return;
        }
        global $wpdb;
        $type = $_REQUEST['type'];
        echo $type;
        switch ($type) {
            case 'new':
                if (!isset($_REQUEST['reason']) || !isset($_REQUEST['time_start']) || !isset($_REQUEST['time_end'])) {
                    echo "Missing values";
                    exit(1);
                }
                $reason = $wpdb->_real_escape($_REQUEST['reason']);
                $time_start = $wpdb->_real_escape($_REQUEST['time_start']);
                $time_end = $wpdb->_real_escape($_REQUEST['time_end']);
                $uid = get_current_user_id();

                $query = "
                    INSERT INTO wp_timeoff
                    (`userid`, `reason`, `time_start`, `time_end`)
                    VALUES ('$uid', '$reason', '$time_start', '$time_end');
                ";
                $wpdb->query($query);
                break;
            case 'update':
                if (!isset($_REQUEST['tid']) || !isset($_REQUEST['reason']) || !isset($_REQUEST['time_start']) || !isset($_REQUEST['time_end'])) {
                    echo "Missing values";
                    exit(1);
                }
                $tid = $wpdb->_real_escape($_REQUEST['tid']);
                $reason = $wpdb->_real_escape($_REQUEST['reason']);
                $time_start = $wpdb->_real_escape($_REQUEST['time_start']);
                $time_end = $wpdb->_real_escape($_REQUEST['time_end']);

                $query = "
                    UPDATE wp_timeoff
                    SET reason='$reason', time_start='$time_start', time_end='$time_end'
                    WHERE id='$tid'
                ";

                $wpdb->query($query);
                break;
            case 'delete':
                if (!isset($_REQUEST['tid'])) {
                    echo "Missing value";
                    exit(1);
                }

                $tid = $wpdb->_real_escape($_REQUEST['tid']);
                $query = "
                    DELETE FROM wp_timeoff
                    WHERE id='$tid';
                ";
                $wpdb->query($query);
                break;

            default:
                echo "Invalid type.";
                exit(1);
        }
    }

}
