<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc;

final class Init
{
	/**
	 * Store all classes this plugin needs to register.
	 */
	public static function get_services()
	{
		return [
			Pages\Admin::class,
			Base\Enqueue::class,
            Base\SettingLinks::class,

		];
	}
	/**
	 * Calls register function for all services.
	 */
	static function register_services()
	{
		foreach (self::get_services() as $class) 
		{
			$service = self::instantiate($class);
			if (method_exists($service, 'register'))
			{
				$service->register();
			}
		}
	}
	/**
	 * Initialise a class
	 * @param class $class, class from services array.
	 */
	private static function instantiate($class)
	{
		return new $class();
	}
}
