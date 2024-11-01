<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://snufel.com/team
 * @since      1.0.0
 *
 * @package    Snufel_Free_Guest
 * @subpackage Snufel_Free_Guest/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Snufel_Free_Guest
 * @subpackage Snufel_Free_Guest/includes
 * @author     Snufel <contact@snufel.com>
 */
class Snufel_Free_Guest_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'snufel-free-guest',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
