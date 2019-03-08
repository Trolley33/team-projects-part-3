<?php

/**
 * @package Helpdesk Plugin
 */

/**
Plugin Name: Helpdesk Plugin
Description: Plugin for problem creation and searching.
Version: 0.0.1
Author: Team 14
Author URI: https://github.com/Goregius/CMS-Team14
License: GPLv2 or later
Text Domain: helpdesk
*/

// If accessed from anywhere except wordpress, stop.
if (!defined('ABSPATH')) 
{
	die;
}

// If we have autoload installed, use it.
if (file_exists(dirname(__FILE__). '/vendor/autoload.php'))
{
	require_once dirname(__FILE__). '/vendor/autoload.php';
}

// Register functions for
function activate_helpdesk_plugin()
{
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activate_helpdesk_plugin');

function deactivate_helpdesk_plugin()
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_helpdesk_plugin');

// Run plugin register function.
if (class_exists('Inc\\Init'))
{
	Inc\Init::register_services();
}