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
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        self::create_followed_tickets_table();
        self::create_timeoff_table();

    }

    static function create_followed_tickets_table () {
        // Create Followed Tickets Table
        $sql =
            "CREATE TABLE IF NOT EXISTS wp_followed_tickets (".
            "id INT(11) NOT NULL auto_increment,".
            "postid INT(11) NOT NULL ,".
            "userid INT(10) NOT NULL default '0',".
            "PRIMARY KEY (id),".
            "KEY rating_followed (postid, userid)) ";

        dbDelta($sql);
    }

    static function create_timeoff_table () {
        $sql =
            "CREATE TABLE IF NOT EXISTS wp_timeoff (".
            "id INT(11) NOT NULL auto_increment,".
            "userid INT(10) NOT NULL default '0',".
            "reason VARCHAR(100) NOT NULL,".
            "time_start DATETIME, ".
            "time_end DATETIME, ".
            "PRIMARY KEY (id))";

        dbDelta($sql);
    }
}
