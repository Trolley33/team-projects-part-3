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

class HelpdeskPlugin
{
	function __construct() 
	{
		add_action('init', array($this, 'create_problem_post'));
	}

	function register()
	{
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

		add_action('admin_menu', array($this, 'add_admin_pages'));
	}

	function add_admin_pages ()
	{
		add_menu_page( "Helpdesk Page", "Helpdesk", 'manage_options', "helpdesk", array($this, 'admin_index'), '', 110 );
	}

	function admin_index()
	{
		require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
	}

	function activate()
	{
		// Generate custom post.
		$this->create_problem_post();
		// Flush DB
		flush_rewrite_rules();
	}

	function deactivate()
	{
		// Flush DB
		flush_rewrite_rules();
	}

	function create_problem_post() 
	{
		register_post_type('problem', ['public'=>true, 'label'=>'Problems']);
	}

	function enqueue()
	{
		//enqueue all scripts
		wp_enqueue_style( 'helpdesk_style', plugins_url('/assets/style.css', __FILE__) );
		wp_enqueue_script( 'helpdesk_script', plugins_url('/assets/script.js', __FILE__) );
	}
}

$helpdeskPlugin = new HelpdeskPlugin();
$helpdeskPlugin->register();

// activation
register_activation_hook(__FILE__, array($helpdeskPlugin, 'activate')); 

// deactivate
register_deactivation_hook(__FILE__, array($helpdeskPlugin, 'deactivate')); 
