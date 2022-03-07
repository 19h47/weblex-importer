<?php

/**
 * The settings of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    WebLex_Importer
 * @subpackage WebLex_Importer/admin
 */

/**
 * Class WebLex_Importer_Settings
 *
 */
class WebLex_Importer_Settings {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'WPPB Demo' menu.
	 */
	public function setup_plugin_options_menu() {

		//Add the menu to the Plugins set of menu items
		add_plugins_page(
			__( 'WebLex Importer Options', 'weblex-importer' ),
			__( 'WebLex Importer Options', 'weblex-importer' ),
			'manage_options',
			'weblex_importer_options',
			array( $this, 'render_settings_page_content' )
		);

	}


	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<div class="wrap">

			<h2><?php _e( 'WebLex Importer Options', 'weblex-importer' ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
			<?php

			settings_fields( 'weblex_importer_options' );
			do_settings_sections( 'weblex_importer_options' );

			submit_button();

			?>
			</form>

		</div><!-- /.wrap -->
			<?php
	}


	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the 'wppb-demo_initialize_theme_options' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function general_options_callback() {
		$options = get_option( 'weblex_importer_options' );

		echo '<p>' . __( 'Enter URLs for WebLex RSS feed.', 'weblex-importer' ) . '</p>';
	}


	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {

		add_settings_section(
			'general_settings_section',                 // ID used to identify this section and with which to register options
			__( 'RSS feeds', 'weblex-importer' ),         // Title to be displayed on the administration page
			array( $this, 'general_options_callback' ), // Callback used to render the description of the section
			'weblex_importer_options'                   // Page on which to add this section of options
		);

		$feeds = array(
			array(
				'id'          => 'actus',
				'label'       => __( 'Actus', 'weblex-importer' ),
				'description' => __( 'Les actualités', 'weblex-importer' ),
				'slug'        => array( 'Les actualités', 'actus', 'actualites' ),
			),
			array(
				'id'          => 'agenda',
				'label'       => __( 'Agenda', 'weblex-importer' ),
				'description' => __( "L'agenda fiscal et social", 'weblex-importer' ),
				'slug'        => array( "L'agenda fiscal et social", 'agenda' ),
			),
			array(
				'id'          => 'fiches',
				'label'       => __( 'Fiches', 'weblex-importer' ),
				'description' => __( 'Les fiches pratiques', 'weblex-importer' ),
				'slug'        => array( 'Les fiches pratiques', 'fiches' ),
			),
			array(
				'id'          => 'indicateurs',
				'label'       => __( 'Indicateurs', 'weblex-importer' ),
				'description' => __( 'Les indicateurs chiffres et barèmes', 'weblex-importer' ),
				'slug'        => array( 'Les indicateurs chiffres et barèmes', 'indicateurs' ),
			),
			array(
				'id'          => 'phdj',
				'label'       => __( 'La petite Histoire du Jour', 'weblex-importer' ),
				'description' => __( 'La petite histoire du jour', 'weblex-importer' ),
				'slug'        => array( 'La petite histoire du jour', 'petite-histoire-du-jour' ),
			),
			array(
				'id'          => 'quiz-hebdo',
				'label'       => __( 'Quiz Hebdo', 'weblex-importer' ),
				'description' => __( 'Le Quiz Hebdo', 'weblex-importer' ),
				'slug'        => array( 'Le Quiz Hebdo', 'quiz-hebdo', 'le-quiz-hebdo' ),
			),
		);

		foreach ( $feeds as $feed ) {
			add_settings_field(
				'weblex_importer_option_' . $feed['id'],
				$feed['label'],
				array( $this, 'save_weblex_feed' ),
				'weblex_importer_options',
				'general_settings_section',
				array(
					'description' => $feed['description'],
					'id'          => $feed['id'],
					'slug'        => $feed['slug'],
				)
			);
		}

		register_setting( 'weblex_importer_options', 'weblex_importer_options' );
	}


	/**
	 * Save WebLex feed
	 *
	 * @param array $args
	 */
	public function save_weblex_feed( array $args ) {
		$options = get_option( 'weblex_importer_options' );
		$term    = $this->get_tag( $args['slug'] );

		var_dump( $term );

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-importer-admin-input.php';
	}


	/**
	 * Get Term
	 */
	public function get_tag( $slug ) {
		$term = '';

		if ( is_array( $slug ) ) {
			foreach ( $slug as $s ) {
				$term = get_term_by( 'slug', sanitize_title( $s ), 'weblex-importer-tag' );

				if ( is_object( $term ) ) {
					return $term;
				}
			}
		}

		return get_term_by( 'slug', sanitize_title( $slug ), 'weblex-importer-tag' );
	}
}
