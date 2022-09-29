<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/19h47/weblex-importer/
 * @since             0.0.0
 * @package           Weblex_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Weblex Importer
 * Plugin URI:        https://github.com/19h47/weblex-importer/
 * Description:       Import posts from an Weblex RSS feed.
 * Version:           0.6.1
 * Author:            Jérémy Levron
 * Author URI:        https://19h47.fr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       webleximporter
 * Domain Path:       /languages
 * Tags:              importer, rss
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WEBLEX_IMPORTER_DIR_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-weblex-importer-activator.php
 */
function activate_weblex_importer() {
	require_once WEBLEX_IMPORTER_DIR_PATH . 'includes/class-weblex-importer-activator.php';
	Weblex_Importer_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-weblex-importer-deactivator.php
 */
function deactivate_weblex_importer() {
	require_once WEBLEX_IMPORTER_DIR_PATH . 'includes/class-weblex-importer-deactivator.php';
	Weblex_Importer_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_weblex_importer' );
register_deactivation_hook( __FILE__, 'deactivate_weblex_importer' );


add_action( 'upgrader_process_complete', 'update_weblex_importer', 10, 2 );

/**
 * Update Weblex Importer
 *
 * @param WP_Upgrader $upgrader WP_Upgrader instance. In other contexts this might be a Theme_Upgrader, Plugin_Upgrader, Core_Upgrade, or Language_Pack_Upgrader instance.
 * @param array       $hook_extra Array of bulk item update data.
 *
 * @see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
 */
function update_weblex_importer( WP_Upgrader $upgrader, array $hook_extra ) {
	$current_plugin_path_name = plugin_basename( __FILE__ );

	if ( 'update' === $hook_extra['action'] && 'plugin' === $hook_extra['type'] ) {
		foreach ( $hook_extra['plugins'] as $plugin ) {
			if ( $plugin === $current_plugin_path_name ) {
				flush_rewrite_rules();
			}
		}
	}
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WEBLEX_IMPORTER_DIR_PATH . 'includes/class-weblex-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.0
 */
function run_weblex_importer() {
	require WEBLEX_IMPORTER_DIR_PATH . 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

	$plugin = new Weblex_Importer();
	$plugin->run();

	$update_checker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/19h47/weblex-importer/',
		__FILE__,
		'weblex-importer'
	);

	$update_checker->setBranch( 'wordpress-plugin' );
}

run_weblex_importer();
