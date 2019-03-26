<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Helpdesk
 * @subpackage Helpdesk/includes
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// unregister the post type, so the rules are no longer in memory
		unregister_post_type( 'book' );
		// clear the permalinks to remove our post type's rules from the database
		flush_rewrite_rules();
	}

}
