<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package           WebLexImporter
 * @package           WebLexImporter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.0
 * @package           WebLexImporter
 * @package           WebLexImporter/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class WebLex_Importer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      WebLex_Importer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'weblex-importer';
		$this->version     = '0.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WebLex_Importer_Loader. Orchestrates the hooks of the plugin.
	 * - WebLex_Importer_i18n. Defines internationalization functionality.
	 * - WebLex_Importer_Admin. Defines all hooks for the admin area.
	 * - WebLex_Importer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-weblex-importer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-weblex-importer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-importer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-weblex-importer-public.php';

		$this->loader = new WebLex_Importer_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WebLex_Importer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WebLex_Importer_I18n();
		$plugin_i18n->set_domain( 'weblex-importer' );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin      = new WebLex_Importer_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings   = new WebLex_Importer_Settings( $this->get_plugin_name(), $this->get_version() );
		$plugin_import     = new WebLex_Importer_Import( $this->get_plugin_name(), $this->get_version() );
		$plugin_post       = new WebLex_Importer_Post( $this->get_plugin_name(), $this->get_version() );
		$plugin_taxonomies = new WebLex_Importer_Taxonomies( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );

		$this->loader->add_action( 'update_option_weblex_importer_options', $plugin_import, 'update_options', 10, 3 );
		$this->loader->add_action( 'weblex_importer_cron_import', $plugin_import, 'cron_update_options' );

		$this->loader->add_action( 'init', $plugin_post, 'register', 10, 0 );
		$this->loader->add_action( 'admin_head', $plugin_post, 'css' );
		$this->loader->add_action( 'manage_weblex-importer-post_posts_custom_column', $plugin_post, 'render_custom_columns', 10, 2 );
		$this->loader->add_filter( 'bulk_post_updated_messages', $plugin_post, 'bulk_updated_messages', 10, 2 );
		$this->loader->add_filter( 'manage_weblex-importer-post_posts_columns', $plugin_post, 'add_custom_columns' );

		$this->loader->add_action( 'init', $plugin_taxonomies, 'register', 10, 0 );
		$this->loader->add_action( 'pre_get_posts', $plugin_taxonomies, 'pre_get_weblex_importer_posts', 10, 1 );

		add_action( '', 'customize_customtaxonomy_archive_display' );

		$this->loader->add_filter( 'post_updated_messages', $plugin_post, 'updated_messages', 10, 1 );
		$this->loader->add_filter( 'bulk_post_updated_messages', $plugin_post, 'bulk_updated_messages', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public          = new WebLex_Importer_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_template_loader = new WebLex_Importer_Template_Loader( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'template_include', $plugin_template_loader, 'template_loader', 10, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.0
	 * @return    WebLex_Importer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
