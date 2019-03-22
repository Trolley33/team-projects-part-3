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
    require_once dirname(__FILE__) . '/inc/Shortcodes/shortcode-all-tickets.php';
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
	add_filter('plugins_loaded', 'test');
}

function test()
{

    $tag_args = array(
        'name' => 'tags',
        'args' => array(
            'show_column' => true,
            'sortable_column' => true,
            'title' => __( 'Problem Type', 'helpdesk' ),
            'label' =>  __( 'Problem Type', 'helpdesk' ),
            'label_plural' => __( 'Problem Types', 'helpdesk' ),
            'order'		 => '0',
        )
    );

    wpas_add_custom_taxonomy($tag_args['name'], $tag_args['args']);

/*
    echo "
    <div class='wpas-form-group' id='wpas_ticket_priority_wrapper'>
        <label for='ticket_tags'>Tags</label>
        <select id='ticket_tags' class='wpas-form-control select2-pillbox' name='tags[]' multiple='multiple'><option value=''>Please select</option>
            <option value='printer'>Printer</option>
        
            <option value='keyboard'>Keyboard</option>
        
            <option value='monitor'>Monitor</option>
        </select>
    </div>
    
	";
*/
}