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
			__( 'WebLex Importer Options', 'webleximporter' ),
			__( 'WebLex Importer Options', 'webleximporter' ),
			'manage_options',
			'weblex_importer_options',
			array( $this, 'render_settings_page_content' )
		);

	}


	/**
	 * Renders a simple page to display for the theme menu defined above.
	 *
	 * @param string $active_tab The active tab.
	 */
	public function render_settings_page_content( string $active_tab = '' ) : void {
		?>
		<div class="wrap">

			<h2><?php _e( 'WebLex Importer Options', 'webleximporter' ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
			<?php

			settings_fields( 'webLex_importer_options' );
			do_settings_sections( 'webLex_importer_options' );

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

		echo '<p>' . __( 'Enter URLs for WebLex RSS feed.', 'WeblexImporter' ) . '</p>';
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
			__( 'RSS feeds', 'webleximporter' ),         // Title to be displayed on the administration page
			array( $this, 'general_options_callback' ), // Callback used to render the description of the section
			'webLex_importer_options'                   // Page on which to add this section of options
		);

		$feeds = array(
			array(
				'id'          => 'actus',
				'label'       => __( 'Actus', 'webleximporter' ),
				'description' => __( 'Les actualités', 'webleximporter' ),
				'slug'        => array( 'ACTUALITES', 'Les actualités', 'actus', 'actualites' ),
			),
			array(
				'id'          => 'agenda',
				'label'       => __( 'Agenda', 'webleximporter' ),
				'description' => __( "L'agenda fiscal et social", 'webleximporter' ),
				'slug'        => array( 'AGENDA', "L'agenda fiscal et social", 'agenda' ),
			),
			array(
				'id'          => 'fiches',
				'label'       => __( 'Fiches', 'webleximporter' ),
				'description' => __( 'Les fiches pratiques', 'webleximporter' ),
				'slug'        => array( 'Les fiches pratiques', 'fiches' ),
			),
			array(
				'id'          => 'indicateurs',
				'label'       => __( 'Indicateurs', 'webleximporter' ),
				'description' => __( 'Les indicateurs chiffres et barèmes', 'webleximporter' ),
				'slug'        => array( 'INDICATEURS, CHIFFRES ET BAREMES', 'Les indicateurs chiffres et barèmes', 'indicateurs' ),
			),
			array(
				'id'          => 'phdj',
				'label'       => __( 'La petite Histoire du Jour', 'webleximporter' ),
				'description' => __( 'La petite histoire du jour', 'webleximporter' ),
				'slug'        => array( 'LA PETITE HISTOIRE DU JOUR', 'La petite histoire du jour', 'petite-histoire-du-jour' ),
			),
			array(
				'id'          => 'quiz-hebdo',
				'label'       => __( 'Quiz Hebdo', 'webleximporter' ),
				'description' => __( 'Le Quiz Hebdo', 'webleximporter' ),
				'slug'        => array( 'Le Quiz Hebdo', 'quiz-hebdo', 'le-quiz-hebdo' ),
			),
		);

		foreach ( $feeds as $feed ) {
			add_settings_field(
				'WebLex_Importer_option_' . $feed['id'],
				$feed['label'],
				array( $this, 'save_weblex_feed' ),
				'webLex_importer_options',
				'general_settings_section',
				array(
					'description' => $feed['description'],
					'id'          => $feed['id'],
					'slug'        => $feed['slug'],
				)
			);
		}

		register_setting( 'weblex_importer_options', 'webLex_importer_options' );
	}


	/**
	 * Save WebLex feed
	 *
	 * @param array $args
	 */
	public function save_weblex_feed( array $args ) {
		$options = get_option( 'webLex_importer_options' );
		$term    = $this->get_tag( $args['slug'] );

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-importer-admin-input.php';
	}


	/**
	 * Get Term
	 *
	 * @param mixed $slug
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

			return $term;
		}

		return get_term_by( 'slug', sanitize_title( $slug ), 'weblex-importer-tag' );
	}
}
