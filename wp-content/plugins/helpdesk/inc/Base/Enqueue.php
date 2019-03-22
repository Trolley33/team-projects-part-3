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
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );
	}

	/**
	 * Load css & js file(s).
	 */
	function enqueue()
	{
        // Enqueue style scripts.
        // wp_enqueue_style('datatables_style', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css');
        wp_enqueue_style( 'helpdesk_style', $this->plugin_url . 'assets/style.css');
        //wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');
        // wp_enqueue_style('overlay-subtle-style', 'http://localhost/wp-content/plugins/awesome-support/themes/default/css/overlay-subtle.css?ver=5.8.0');

        // Enqueue JS scripts.
        // wp_enqueue_script('datatables_script', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js');
        //wp_enqueue_script('jquery_script', 'http://code.jquery.com/jquery-3.3.1.min.js');
        //wp_enqueue_script('select2-script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js');
        wp_enqueue_script( 'helpdesk_script', $this->plugin_url . 'assets/script.js');
    }

}
