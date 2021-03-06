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
 * @package           WebLex_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       WebLex Importer
 * Plugin URI:        https://github.com/19h47/weblex-importer/
 * Description:       Import posts from an WebLex RSS feed.
 * Version:           0.1.7
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
	WebLex_Importer_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-weblex-importer-deactivator.php
 */
function deactivate_weblex_importer() {
	require_once WEBLEX_IMPORTER_DIR_PATH . 'includes/class-weblex-importer-deactivator.php';
	WebLex_Importer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_weblex_importer' );
register_deactivation_hook( __FILE__, 'deactivate_weblex_importer' );


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

	$plugin = new WebLex_Importer();
	$plugin->run();

	$update_checker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/19h47/weblex-importer/',
		__FILE__,
		'weblex-importer'
	);

	$update_checker->setBranch( 'wordpress-plugin' );
}

run_weblex_importer();
