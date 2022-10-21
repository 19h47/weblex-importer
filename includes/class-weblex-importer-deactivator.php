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

		$weblex_importer_posts = get_posts(
			array(
				'post_type'           => 'weblex-importer-post',
				'posts_per_page'      => -1,
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'suppress_filters'    => true,
			)
		);

		foreach ( $weblex_importer_posts as $post ) {
			wp_delete_post( $post->ID, true );
		}
	}
}
