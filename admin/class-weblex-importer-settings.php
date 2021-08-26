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
			__( 'WebLex Importer Options', 'weblex-importer' ),                    // The title to be displayed in the browser window for this page.
			__( 'WebLex Importer Options', 'weblex-importer' ),                    // The text to be displayed for this menu item
			'manage_options',                   // Which type of users can see this menu item
			'weblex_importer_options',            // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content' )               // The name of the function to call when rendering this menu's page
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
				'description' => __( 'Flux pour WebLex Actus.', 'weblex-importer' ),
			),
			array(
				'id'          => 'agenda',
				'label'       => __( 'Agenda', 'weblex-importer' ),
				'description' => __( 'Flux pour WebLex Agenda.', 'weblex-importer' ),
			),
			array(
				'id'          => 'fiches',
				'label'       => __( 'Fiches', 'weblex-importer' ),
				'description' => __( 'Flux pour WebLex Fiches.', 'weblex-importer' ),
			),
			array(
				'id'          => 'indicateurs',
				'label'       => __( 'Indicateurs', 'weblex-importer' ),
				'description' => __( 'Flux pour WebLex Indicateurs.', 'weblex-importer' ),
			),
			array(
				'id'          => 'phdj',
				'label'       => __( 'Petite Histoire du Jour', 'weblex-importer' ),
				'description' => __( 'Flux pour WebLex PHDJ.', 'weblex-importer' ),
			),
			array(
				'id'          => 'quiz-hebdo',
				'label'       => __( 'Quiz Hebdo', 'weblex-importer' ),
				'description' => __( 'Flux pour WebLex Quiz Hebdo.', 'weblex-importer' ),
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
				)
			);
		}

		register_setting(
			'weblex_importer_options',
			'weblex_importer_options',
			// 'sanitize_text_field'
		);
	}


	/**
	 * Save WebLex feed
	 *
	 * @param array $args
	 */
	public function save_weblex_feed( array $args ) {
		$options = get_option( 'weblex_importer_options' );
		$term    = get_term_by( 'slug', sanitize_title( $args['description'] ), 'weblex-importer-tag' );

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-importer-admin-input.php';
	}
}
