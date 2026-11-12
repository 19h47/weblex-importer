<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Loads the classes of the admin area.
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Weblex_Importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->load_dependencies();
	}


	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Weblex_Importer_Settings. Registers the admin settings and page.
	 * - Weblex_Importer_Import.
	 * - Weblex_Importer_Post.
	 * - Weblex_Importer_Taxonomy.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-weblex-importer-settings.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-weblex-importer-import.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-weblex-importer-post.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-weblex-importer-taxonomies.php';
	}
}
