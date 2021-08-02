<?php

/**
 * The settings of the plugin.
 *
 * @link       https://github.com/19h47/weblex-rss-feed/
 * @since      0.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class WebLex_RSS_Feed_Settings {

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
			__( 'WebLex RSS Feed Options', 'weblex-rss-feed' ),                    // The title to be displayed in the browser window for this page.
			__( 'WebLex RSS Feed Options', 'weblex-rss-feed' ),                    // The text to be displayed for this menu item
			'manage_options',                   // Which type of users can see this menu item
			'weblex_rss_feed_options',            // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content' )               // The name of the function to call when rendering this menu's page
		);

	}


	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<div class="wrap">

			<h2><?php _e( 'WebLexx RSS Feed Options', 'weblex-rss-feed' ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php

				settings_fields( 'weblex_rss_feed_options' );
				do_settings_sections( 'weblex_rss_feed_options' );

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
		$options = get_option( 'weblex_rss_feed_options' );

		echo '<p>' . __( 'Enter URLs for WebLex RSS feed.', 'weblex-rss-feed' ) . '</p>';
	} // end general_options_callback


	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {

		add_settings_section(
			'general_settings_section',                     // ID used to identify this section and with which to register options
			__( 'RSS feeds', 'weblex-rss-feed' ),                // Title to be displayed on the administration page
			array( $this, 'general_options_callback' ),      // Callback used to render the description of the section
			'weblex_rss_feed_options'                     // Page on which to add this section of options
		);

		$feeds = array(
			array(
				'id'          => 'actus',
				'label'       => __( 'Actus', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex Actus.', 'weblex-rss-feed' ),
			),
			array(
				'id'          => 'agenda',
				'label'       => __( 'Agenda', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex Agenda.', 'weblex-rss-feed' ),
			),
			array(
				'id'          => 'fiches',
				'label'       => __( 'Fiches', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex Fiches.', 'weblex-rss-feed' ),
			),
			array(
				'id'          => 'indicateurs',
				'label'       => __( 'Indicateurs', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex Indicateurs.', 'weblex-rss-feed' ),
			),
			array(
				'id'          => 'phdj',
				'label'       => __( 'Petite Histoire du Jour', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex PHDJ.', 'weblex-rss-feed' ),
			),
			array(
				'id'          => 'quiz-hebdo',
				'label'       => __( 'Quiz Hebdo', 'weblex-rss-feed' ),
				'description' => __( 'Flux pour WebLex Quiz Hebdo.', 'weblex-rss-feed' ),
			),
		);

		foreach ( $feeds as $feed ) {
			add_settings_field(
				'weblex_' . $feed['id'],
				$feed['label'],
				array( $this, 'save_weblex_feed' ),
				'weblex_rss_feed_options',
				'general_settings_section',
				array(
					'description' => $feed['description'],
					'id'          => $feed['id'],
				)
			);
		}

		// Finally, we register the fields with WordPress
		register_setting(
			'weblex_rss_feed_options',
			'weblex_rss_feed_options',
			array( $this, 'validate_input' )
		);

	}


	/**
	 * Save WebLex feed
	 *
	 * @param array $args
	 */
	public function save_weblex_feed( array $args ) {

		// First, we read the options collection
		$options = get_option( 'weblex_rss_feed_options' );

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weblex-rss-feed-admin-input.php';

	} // end save_weblex_feed

	/**
	 *
	 */
	public function validate_input( array $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if
		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'weblex_rss_feed_validate_input', $output, $input );

	} // end validate_input
}
