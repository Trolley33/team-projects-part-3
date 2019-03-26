<?php

/*
 * Trigger file on plugin uninstall.
 *
 * @package Test Plugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) 
{
	die;
}

global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book';");
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);");
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts);");