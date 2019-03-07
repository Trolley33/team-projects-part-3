<?php

/**
 * @package Helpdesk Plugin
 */

namespace Inc\Base;

class Enqueue
{	
	/**
	 * Register script handler.
	 */
	public function register()
	{
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
	}

	/**
	 * Load css & js file(s).
	 */
	function enqueue()
	{
		//enqueue all scripts
		wp_enqueue_style( 'helpdesk_style', PLUGIN_URL . 'assets/style.css');

		wp_enqueue_script( 'helpdesk_script', PLUGIN_URL . 'assets/script.js');
	}

}