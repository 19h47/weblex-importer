<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/19h47/weblex-rss-feed/
 * @since             0.0.0
 * @package           WebLexRSSFeed
 *
 * @wordpress-plugin
 * Plugin Name:       WebLex RSS Feed
 * Plugin URI:        https://github.com/19h47/weblex-rss-feed/
 * Description:       WebLex RSS Feed.
 * Version:           0.0.0
 * Author:            Jérémy Levron
 * Author URI:        https://19h47.fr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weblexrssfeed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-weblex-rss-feed-activator.php
 */
function activate_weblex_rss_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weblex-rss-feed-activator.php';
	weblex_rss_feed_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-weblex-rss-feed-deactivator.php
 */
function deactivate_weblex_rss_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weblex-rss-feed-deactivator.php';
	weblex_rss_feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_weblex_rss_feed' );
register_deactivation_hook( __FILE__, 'deactivate_weblex_rss_feed' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-weblex-rss-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.0
 */
function run_weblex_rss_feed() {

	$plugin = new weblex_rss_feed();
	$plugin->run();

}
run_weblex_rss_feed();
