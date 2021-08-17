<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      1.0.0
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
class WebLex_Importer_Public {

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

		$this->load_dependencies();
	}


	/**
	 * Load the required dependencies for the Public facing functionality.
	 *
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function load_dependencies() : void {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-weblex-importer-template-loader.php';
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WebLex_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WebLex_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weblex-importer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WebLex_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WebLex_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weblex-importer-public.js', array( 'jquery' ), $this->version, false );

	}

}
