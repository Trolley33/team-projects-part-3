<?php

/**
 * @package Helpdesk Plugin
 */

namespace Inc\Base;

class Enqueue extends BaseController
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
		wp_enqueue_style( 'helpdesk_style', $this->plugin_url . 'assets/style.css');

		wp_enqueue_script( 'helpdesk_script', $this->plugin_url . 'assets/script.js');
	}

}