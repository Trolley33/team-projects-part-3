<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // clear the permalinks after the post type has been registered
        flush_rewrite_rules();

        // Create Post Ratings Table
        $create_sql = "CREATE TABLE wp_followed_tickets (".
            "id INT(11) NOT NULL auto_increment,".
            "postid INT(11) NOT NULL ,".
            "userid INT(10) NOT NULL default '0',".
            "PRIMARY KEY (id),".
            "KEY rating_followed (postid, userid)) ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $create_sql );
    }
}
