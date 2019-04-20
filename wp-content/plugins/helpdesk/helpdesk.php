<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Helpdesk
 *
 * @wordpress-plugin
 * Plugin Name:       Helpdesk Plugin
 * Plugin URI:        http://example.com/helpdesk-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       helpdesk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('HELPDESK_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-helpdesk-activator.php
 */
function activate_helpdesk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-helpdesk-activator.php';
    Helpdesk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-helpdesk-deactivator.php
 */
function deactivate_helpdesk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-helpdesk-deactivator.php';
    Helpdesk_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_helpdesk');
register_deactivation_hook(__FILE__, 'deactivate_helpdesk');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-helpdesk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_helpdesk()
{

    $plugin = new Helpdesk();
    $plugin->run();

}

function the_followed_thread()
{
    $userid = get_current_user_id();
    $postid = get_the_ID();

    // Check if user is already following thread.
    global $wpdb;

    $query = "SELECT id FROM wp_followed_tickets WHERE postid='$postid' AND userid='$userid'";
    $result = $wpdb->get_results($query);

    $following = true;
    if (count($result) == 0) {
        $following = false;
    }

    // Render 'unfollow' button if following.
    if ($following == true) {
        echo "
        <button id='wphd-follow-button' class='wpas-btn wpas-btn-default' onclick='unfollow_thread($userid, $postid)'>Unfollow this ticket?</button>   
        ";
    } else {
        // Render follow button else.
        echo "   
        <button id='wphd-follow-button' class='wpas-btn wpas-btn-default' onclick='follow_thread($userid, $postid)'>Follow this ticket?</button>    
        ";
    }

}

run_helpdesk();
