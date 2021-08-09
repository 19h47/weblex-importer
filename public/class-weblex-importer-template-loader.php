<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      1.0.0
 *
 * @package           WebLexRSSImporter
 * @subpackage WebLexRSSImporter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package           WebLexRSSImporter
 * @subpackage WebLexRSSImporter/public
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class WebLex_Importer_Template_Loader {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Template
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/template_include/
	 * @return string
	 */
	function template( string $template ) : string {
		return $template;
	}
}
