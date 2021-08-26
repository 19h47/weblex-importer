<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package           WebLexImporter
 * @subpackage WebLexImporter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package           WebLexImporter
 * @subpackage WebLexImporter/public
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
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the theme's.
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/template_include/
	 *
	 * @return string
	 */
	function template_loader( string $template ) : string {

		if ( is_tax( 'weblex-importer-tag' ) ) {
			$search_files = array( 'taxonomy-weblex-importer-tag.php' );
			$template     = locate_template( $search_files );

			if ( $template ) {
				return $template;
			} else {
				$template = WEBLEX_IMPORTER_DIR_PATH . 'templates/taxonomy-weblex-importer-tag.php';
			}
		}

		if ( is_tax( 'weblex-importer-category' ) ) {
			$search_files = array( 'taxonomy-weblex-importer-category.php' );
			$template     = locate_template( $search_files );

			if ( $template ) {
				return $template;
			} else {
				$template = WEBLEX_IMPORTER_DIR_PATH . 'templates/taxonomy-weblex-importer-category.php';
			}
		}

		return $template;
	}
}
