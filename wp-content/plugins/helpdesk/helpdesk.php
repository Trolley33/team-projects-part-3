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

if (!defined('ABSPATH')) 
{
	die;
}

if (file_exists(dirname(__FILE__). '/vendor/autoload.php'))
{
	require_once dirname(__FILE__). '/vendor/autoload.php';
}

define('PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('PLUGIN', plugin_basename( __FILE__ ));

if (class_exists('Inc\\Init'))
{
	Inc\Init::register_services();
}