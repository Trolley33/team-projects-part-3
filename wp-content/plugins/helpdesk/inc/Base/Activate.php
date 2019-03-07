<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc\Base;

class Activate
{
	public static function activate()
	{
		// Do something when plugin is activated.

		// Flush database.
		flush_rewrite_rules();
	}
}