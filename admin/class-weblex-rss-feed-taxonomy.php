<?php

/**
 * @link       https://github.com/19h47/weblex-rss-feed/
 * @since      0.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin
 */


/**
 * Product class
 */
class WebLex_RSS_Feed_Taxonomy {

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
	 * Register custom taxonomy
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @return void
	 */
	public function register() : void {

		$labels = array(
			'name'                       => _x( 'Tags', 'member tag general name', 'weblexrssfeed' ),
			'singular_name'              => _x( 'Tag', 'member tag singular name', 'weblexrssfeed' ),
			'search_items'               => __( 'Search Tags', 'weblexrssfeed' ),
			'all_items'                  => __( 'All Tags', 'weblexrssfeed' ),
			'popular_items'              => __( 'Popular Tags', 'weblexrssfeed' ),
			'edit_item'                  => __( 'Edit Tag', 'weblexrssfeed' ),
			'view_item'                  => __( 'View Tag', 'weblexrssfeed' ),
			'update_item'                => __( 'Update Tag', 'weblexrssfeed' ),
			'add_new_item'               => __( 'Add New Tag', 'weblexrssfeed' ),
			'new_item_name'              => __( 'New Tag Name', 'weblexrssfeed' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'weblexrssfeed' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'weblexrssfeed' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'weblexrssfeed' ),
			'not_found'                  => __( 'No tags found.', 'weblexrssfeed' ),
			'no_terms'                   => __( 'No tags', 'weblexrssfeed' ),
			'items_list_navigation'      => __( 'Tags list navigation', 'weblexrssfeed' ),
			'items_list'                 => __( 'Tags list', 'weblexrssfeed' ),
			/* translators: Member tag heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'member tag', 'weblexrssfeed' ),
			'back_to_items'              => __( '&larr; Back to Tags', 'weblexrssfeed' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		);

		register_taxonomy( 'weblex-rss-feed-tag', array( 'weblex-rss-feed-post' ), $args );
	}
}
