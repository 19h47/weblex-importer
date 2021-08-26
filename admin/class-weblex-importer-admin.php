<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    WebLex_Importer
 * @subpackage WebLex_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WebLex_Importer
 * @subpackage WebLex_Importer/admin
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class WebLex_Importer_Admin {

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
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
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
	 * - WebLex_Importer_Settings. Registers the admin settings and page.
	 * - WebLex_Importer_Import.
	 * - WebLex_Importer_Post.
	 * - WebLex_Importer_Taxonomy.
	 *
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function load_dependencies() : void {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-importer-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-importer-import.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-importer-post.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-importer-taxonomies.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.0
	 */
	public function enqueue_styles() : void {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wppb_Demo_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wppb_Demo_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weblex-importer-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wppb_Demo_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wppb_Demo_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weblex-importer-admin.js', array( 'jquery' ), $this->version, false );

	}
}
