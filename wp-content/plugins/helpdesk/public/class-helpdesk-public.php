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

    }

    public function register_book_type()
    {
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
    }

    /**
     * Registration page shortcode.
     */
    public function wpas_sc_all_tickets()
    {
		
        global $wpas_tickets, $post;

        $wpas_tickets = $this->wpas_get_all_tickets_for_shortcode();

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
     * @param none
     *
     * @return array post array of tickets found
     */
    private function wpas_get_all_tickets_for_shortcode()
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

    function wphd_add_custom_fields()
{
    // Grab all tags
    global $wpdb;
    $results = $wpdb->get_results("
SELECT wp_terms.term_id, wp_terms.name FROM wp_terms
JOIN wp_term_taxonomy
ON wp_term_taxonomy.term_id = wp_terms.term_id
WHERE wp_term_taxonomy.taxonomy = 'ticket-tag';
");

    // Convert result array into 'tag-id' => 'tag-name'.
    $options = [];
    foreach ($results as $tag)
    {
        $options[$tag->term_id] = $tag->name;
    }

    // Create custom tag field.
    $tag_args = array(
        'name' => 'tags',
        'args' => array(
            'title' => __( 'Tags', 'helpdesk' ),
            'label' =>  __( 'Tags', 'helpdesk' ),
            'label_plural' => __( 'Tags', 'helpdesk' ),
            'order'      => '0',
            'field_type' => 'select',
            'multiple' => true,
            'select2' => true,
            'options' => $options
        )
    );

    wpas_add_custom_field($tag_args['name'], $tag_args['args']);
}

}
