<?php

/**
 * Plugin Name:       WebLex RSS Feed
 * Plugin URI:        https://github.com/19h47/weblex-rss-feed/
 * Description:       Hey.
 * Version:           1.0.0
 * Author:            SuperFramer
 * Author URI:        https://superframer.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weblexrssfeed
 * Domain Path:       /languages
 *
 * @package WebLexRSSFeed
 */

require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcode.php';

$plugin = new Shortcode();
$plugin->run();
