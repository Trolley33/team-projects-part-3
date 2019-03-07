<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc\Base;

class Deactivate
{
	public static function deactivate()
	{
		// Do something when plugin is deactivated.

		// Flush database.
		flush_rewrite_rules();
	}
}