<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.0
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Weblex_Importer_I18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 * @var      string    $domain    The domain identifier for this plugin.
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since    0.0.0
	 * @param    string $domain    The domain that represents the locale of this plugin.
	 */
	public function set_domain( string $domain ) {
		$this->domain = $domain;
	}

}
