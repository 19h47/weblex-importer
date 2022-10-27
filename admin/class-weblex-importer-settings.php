<?php
/**
 * The settings of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin
 */

/**
 * Class Weblex_Importer_Settings
 */
class Weblex_Importer_Settings {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Set options page
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_options_page/
	 *
	 * @return void
	 */
	public function setup_options_page() : void {
		add_options_page(
			__( 'Weblex Importer', 'webleximporter' ),
			__( 'Weblex Importer', 'webleximporter' ),
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

			<h2><?php esc_html_e( 'Weblex Importer Options', 'webleximporter' ); ?></h2>

			<form method="post" action="options.php">
				<?php

				do_settings_sections( 'weblex_importer_options' );
				settings_fields( 'weblex_importer_options' );

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
	public function feeds_options_callback() {
		echo '<p>' . esc_html__( 'Enter URLs for Weblex RSS feed.', 'webleximporter' ) . '</p>';
	}


	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the 'wppb-demo_initialize_theme_options' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function general_options_callback() {
		echo '<p>' . esc_html__( 'General options.', 'webleximporter' ) . '</p>';
	}


	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {
		add_settings_section(
			'feeds_settings_section',                 // ID used to identify this section and with which to register options.
			__( 'RSS feeds', 'webleximporter' ),         // Title to be displayed on the administration page.
			array( $this, 'feeds_options_callback' ), // Callback used to render the description of the section.
			'weblex_importer_options'                   // Page on which to add this section of options.
		);

		$feeds = array(
			array(
				'id'          => 'actus',
				'label'       => __( 'Actualités', 'webleximporter' ),
				'description' => __( 'Les actualités', 'webleximporter' ),
				'slug'        => array( 'LES ACTUALITES', 'Actualités', 'ACTUALITES', 'Les actualités', 'actus', 'actualites' ),
			),
			array(
				'id'          => 'agenda',
				'label'       => __( "L'agenda fiscal et social", 'webleximporter' ),
				'description' => __( "L'agenda fiscal et social", 'webleximporter' ),
				'slug'        => array( "L'AGENDA", 'AGENDA', 'L’agenda fiscal et social', "L'agenda fiscal et social", "L'agenda fiscal et social", 'agenda' ),
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
				'slug'        => array( 'LES INDICATEURS', 'INDICATEURS, CHIFFRES ET BAREMES', 'Les indicateurs chiffres et barèmes', 'indicateurs' ),
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
				'slug'        => array( 'LE QUIZ HEBDO', 'Le quiz hebdo', 'Le Quiz Hebdo', 'quiz-hebdo', 'le-quiz-hebdo' ),
			),
		);

		foreach ( $feeds as $feed ) {
			add_settings_field(
				'weblex_importer_option_' . $feed['id'],
				$feed['label'],
				array( $this, 'save_weblex_feed_input' ),
				'weblex_importer_options',
				'feeds_settings_section',
				array(
					'description' => $feed['description'],
					'id'          => $feed['id'],
					'slug'        => $feed['slug'],
				)
			);
		}

		add_settings_section(
			'general_settings_section',                 // Slug-name to identify the section. Used in the 'id' attribute of tags.
			__( 'Options', 'webleximporter' ),          // Formatted title of the section. Shown as the heading for the section.
			array( $this, 'general_options_callback' ), // Function that echos out any content at the top of the section (between heading and fields).
			'weblex_importer_options'                   // The slug-name of the settings page on which to show the section. Built-in pages include 'general', 'reading', 'writing', 'discussion', 'media', etc.
		);

		add_settings_field(
			'weblex_importer_post', // Slug-name to identify the field. Used in the 'id' attribute of tags.
			__( 'Post', 'webleximporter' ), // Formatted title of the field. Shown as the label for the field during output.
			array( $this, 'save_weblex_feed_checkbox' ), // Function that fills the field with the desired form inputs. The function should echo its output.
			'weblex_importer_options', // The slug-name of the settings page on which to show the section (general, reading, writing, ...).
			'general_settings_section', // The slug-name of the section of the settings page in which to show the box.
			array(
				'description' => __( 'Import Weblex posts as WordPress posts', 'webleximporter' ),
				'id'          => 'post',
			)
		);

		register_setting( 'weblex_importer_options', 'weblex_importer_options' );
		register_setting( 'weblex_importer_options', 'weblex_importer_feeds' );
	}


	/**
	 * Save Weblex feed input
	 *
	 * @param array $args Args.
	 */
	public function save_weblex_feed_input( array $args ) {
		$options   = get_option( 'weblex_importer_options' );
		$term      = $this->get_tag( $args['slug'] );
		$post_type = weblex_importer_get_post_type();

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-importer-admin-input.php';
	}


	/**
	 * Save Weblex feed checkbox
	 *
	 * @param array $args Args.
	 */
	public function save_weblex_feed_checkbox( array $args ) {
		$options = get_option( 'weblex_importer_options' );

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-importer-admin-checkbox.php';
	}


	/**
	 * Get tag
	 *
	 * @param mixed $slug Slug.
	 */
	public function get_tag( $slug ) {
		$term = '';

		if ( is_array( $slug ) && 'weblex-importer-post' === weblex_importer_get_post_type() ) {
			foreach ( $slug as $s ) {
				$term = get_term_by( 'slug', sanitize_title( $s ), 'weblex-importer-tag' );

				if ( is_object( $term ) ) {
					return $term;
				}
			}
		}

		if ( is_array( $slug ) && 'post' === weblex_importer_get_post_type() ) {
			foreach ( $slug as $s ) {
				$term = get_term_by( 'slug', sanitize_title( $s ), 'post_tag' );

				if ( is_object( $term ) ) {
					return $term;
				}
			}
		}

		return $term;
	}
}
