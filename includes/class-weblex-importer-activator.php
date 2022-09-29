<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.0
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Weblex_Importer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();

		if ( ! wp_next_scheduled( 'weblex_importer_cron_import' ) ) {
			wp_schedule_event( time(), 'daily', 'weblex_importer_cron_import' );
		}
	}
}
