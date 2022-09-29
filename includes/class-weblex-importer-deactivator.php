<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.0.0
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Weblex_Importer_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'weblex_importer_cron_import' );
	}
}
