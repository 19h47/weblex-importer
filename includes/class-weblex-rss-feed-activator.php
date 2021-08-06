<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WeblexRSSFeed
 * @subpackage WeblexRSSFeed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WeblexRSSFeed
 * @subpackage WeblexRSSFeed/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class WebLex_RSS_Feed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}
