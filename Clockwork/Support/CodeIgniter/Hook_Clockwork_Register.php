<?php

namespace Clockwork\Support\CodeIgniter;

/**
 * Singleton class for easy loading of the Clockwork Hooks. Add the following to
 * your application/config/hooks.php file:
 * 
 *     Clockwork\Support\CodeIgniter\Hook_Clockwork_Register::registerHooks($hooks);
 * 
 */
class Hook_Clockwork_Register
{
	public static function registerHooks(&$hooks, $storagePath = null)
	{
		$__hooknames = [
			'pre_system', 
			'pre_controller',
			'post_controller_constructor',
			'post_controller',
			'post_system',
			'display_override'
		];
		
		// Force Autoload and set Storage Path for the Hook_Clockwork
		// class. Also force it to auto-load so that CodeIgniter does
		// not make the attempt.
		Hook_Clockwork::setStoragePath($storagePath);
		
		// Hook into all the necessary hooks for Hook_Clockwork to do
		// it's job. Make sure that other previous hooks are not
		// overwritten.
		foreach ($__hooknames as $__hookname) {
			if (!isset($hook[$__hookname])) {
				$hook[$__hookname] = [];
			}
			if (isset($hook[$__hookname]) && isset($hook[$__hookname]['class'])) {
				$hook[$__hookname] = [0 => $hook[$__hookname]];
			}
			
			$hook[$__hookname][] = [
				'class'		=> 'Clockwork\\Support\\CodeIgniter\\Hook_Clockwork',
				'function'	=> $__hookname,
				'filename'	=> 'Hook_Clockwork.php',
				// Do a bit of hand-waving here so that core/Hooks.php
				// sits well with hooking into a file outside of the
				// application folder.
				'filepath'	=> self::__resolve_filepath(),
				'params'	=> []
			];
			
		}
	}
	
	/**
	 * Helper function to find the common path between the CodeIgniter
	 * APPPATH and the path to the Hook_Clockwork class file.
	 */
	private static function __commonPath($d1, $d2)
	{
		$da1 = explode('/', $d1);
		$da2 = explode('/', $d2);
		
		
		for ($i = 0, $x = min(count($da1), count($da2)); $i < $x; $i++) {
			if ($da1[$i] != $da2[$i]) {
				return implode('/', array_slice($da1, 0, $i));
			}
		}
		
		return implode('/', array_slice($da1, 0, $i));
	}
	
	/**
	 * Resolves the path used for the hook.
	 */
	private static function __resolve_filepath()
	{
		$common		= self::__commonPath(APPPATH, __DIR__);
		$backpath	= substr_count(APPPATH, '/') - substr_count($common, '/');
		
		return $common.'/'.str_repeat('../', $backpath).substr(__DIR__, strlen($common));
	}
}
