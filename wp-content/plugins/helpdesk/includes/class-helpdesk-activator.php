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
    }
}
