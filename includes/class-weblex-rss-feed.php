<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/19h47/weblex-rss-feed/
 * @since      0.0.0
 *
 * @package    WebLexRSSFeed
 * @subpackage WebLexRSSFeed/includes
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
 * @package    WebLexRSSFeed
 * @subpackage WebLexRSSFeed/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class WebLex_RSS_Feed {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.0
	 * @access   protected
	 * @var      WebLex_RSS_Feed_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		$this->plugin_name = 'weblex-rss-feed';
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
	 * - WebLex_RSS_Feed_Loader. Orchestrates the hooks of the plugin.
	 * - WebLex_RSS_Feed_i18n. Defines internationalization functionality.
	 * - WebLex_RSS_Feed_Admin. Defines all hooks for the admin area.
	 * - WebLex_RSS_Feed_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-weblex-rss-feed-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-weblex-rss-feed-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-weblex-rss-feed-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-weblex-rss-feed-public.php';

		$this->loader = new WebLex_RSS_Feed_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WebLex_RSS_Feed_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WebLex_RSS_Feed_i18n();
		$plugin_i18n->set_domain( 'weblex-rss-feed' );

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

		$plugin_admin       = new WebLex_RSS_Feed_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings    = new WebLex_RSS_Feed_Settings( $this->get_plugin_name(), $this->get_version() );
		$plugin_insert_post = new WebLex_RSS_Feed_Insert_Post( $this->get_plugin_name(), $this->get_version() );
		$plugin_post_type   = new WebLex_RSS_Feed_Post_Type( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );

		$this->loader->add_action( 'update_option_weblex_rss_feed_options', $plugin_insert_post, 'init', 10, 3 );

		$this->loader->add_action( 'init', $plugin_post_type, 'register', 10, 0 );

		$this->loader->add_filter( 'post_updated_messages', $plugin_post_type, 'updated_messages', 10, 1 );
		$this->loader->add_filter( 'bulk_post_updated_messages', $plugin_post_type, 'bulk_updated_messages', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WebLex_RSS_Feed_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    WebLex_RSS_Feed_Loader    Orchestrates the hooks of the plugin.
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