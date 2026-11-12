<?php
/**
 * Configuration of the test suite of WordPress: the core installed by Composer, and a database of its own, which the
 * suite empties on each run, named by the environment.
 *
 * @package Weblex_Importer
 */

// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- The suite reads the prefix of its tables from this global.

define( 'ABSPATH', dirname( __DIR__ ) . '/vendor/roots/wordpress-no-content/' );

define( 'DB_NAME', getenv( 'WP_TESTS_DB_NAME' ) ? getenv( 'WP_TESTS_DB_NAME' ) : 'weblex_importer_tests' );
define( 'DB_USER', getenv( 'WP_TESTS_DB_USER' ) ? getenv( 'WP_TESTS_DB_USER' ) : 'root' );
define( 'DB_PASSWORD', getenv( 'WP_TESTS_DB_PASSWORD' ) ? getenv( 'WP_TESTS_DB_PASSWORD' ) : 'root' );
define( 'DB_HOST', getenv( 'WP_TESTS_DB_HOST' ) ? getenv( 'WP_TESTS_DB_HOST' ) : '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

$table_prefix = 'wptests_';

// The themes of the suite from the start, as its bootstrap adds them: the core installed by Composer has none, and its installer looks for one first.
$wp_theme_directories = array( dirname( __DIR__ ) . '/vendor/wp-phpunit/wp-phpunit/data/themedir1' );

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Weblex Importer' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WP_DEBUG', true );
define( 'WPLANG', '' );
