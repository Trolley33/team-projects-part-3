<?php

/**
 * @package Helpdesk Plugin
 */

/**
Plugin Name: Helpdesk PluginBak
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
	add_filter('plugins_loaded', 'wphd_add_custom_fields');
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
            'order'		 => '0',
            'field_type' => 'select',
            'multiple' => true,
            'select2' => true,
            'options' => $options
        )
    );

    wpas_add_custom_field($tag_args['name'], $tag_args['args']);
}