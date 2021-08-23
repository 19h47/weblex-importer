<?php

/**
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    WebLex_Importer
 * @subpackage WebLex_Importer/admin
 */


/**
 * WebLex Importer Taxonomies
 */
class WebLex_Importer_Taxonomies {

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
	 * Register
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @return void
	 */
	public function register() : void {

		$this->register_tag();
		$this->register_category();
	}


	/**
	 * Register tag
	 *
	 * @return void
	 */
	public function register_tag() {
		$labels = array(
			'name'                       => _x( 'Tags', 'member tag general name', 'weblex-importer' ),
			'singular_name'              => _x( 'Tag', 'member tag singular name', 'weblex-importer' ),
			'search_items'               => __( 'Search Tags', 'weblex-importer' ),
			'all_items'                  => __( 'All Tags', 'weblex-importer' ),
			'popular_items'              => __( 'Popular Tags', 'weblex-importer' ),
			'edit_item'                  => __( 'Edit Tag', 'weblex-importer' ),
			'view_item'                  => __( 'View Tag', 'weblex-importer' ),
			'update_item'                => __( 'Update Tag', 'weblex-importer' ),
			'add_new_item'               => __( 'Add New Tag', 'weblex-importer' ),
			'new_item_name'              => __( 'New Tag Name', 'weblex-importer' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'weblex-importer' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'weblex-importer' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'weblex-importer' ),
			'not_found'                  => __( 'No tags found.', 'weblex-importer' ),
			'no_terms'                   => __( 'No tags', 'weblex-importer' ),
			'items_list_navigation'      => __( 'Tags list navigation', 'weblex-importer' ),
			'items_list'                 => __( 'Tags list', 'weblex-importer' ),
			/* translators: Post tag heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'member tag', 'weblex-importer' ),
			'back_to_items'              => __( '&larr; Back to Tags', 'weblex-importer' ),
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

		register_taxonomy( 'weblex-importer-tag', array( 'weblex-importer-post' ), $args );
	}


	/**
	 * Register category
	 *
	 * @return void
	 */
	public function register_category() {
		$labels = array(
			'name'                       => _x( 'Categories', 'member category general name', 'weblex-importer' ),
			'singular_name'              => _x( 'Category', 'member Category singular name', 'weblex-importer' ),
			'search_items'               => __( 'Search Categories', 'weblex-importer' ),
			'all_items'                  => __( 'All Categories', 'weblex-importer' ),
			'popular_items'              => __( 'Popular Categories', 'weblex-importer' ),
			'edit_item'                  => __( 'Edit Category', 'weblex-importer' ),
			'view_item'                  => __( 'View Category', 'weblex-importer' ),
			'update_item'                => __( 'Update Category', 'weblex-importer' ),
			'add_new_item'               => __( 'Add New Category', 'weblex-importer' ),
			'new_item_name'              => __( 'New Category Name', 'weblex-importer' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'weblex-importer' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'weblex-importer' ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', 'weblex-importer' ),
			'not_found'                  => __( 'No categories found.', 'weblex-importer' ),
			'no_terms'                   => __( 'No categories', 'weblex-importer' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'weblex-importer' ),
			'items_list'                 => __( 'Categories list', 'weblex-importer' ),
			/* translators: Post category heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'member tag', 'weblex-importer' ),
			'back_to_items'              => __( '&larr; Back to Categories', 'weblex-importer' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		);

		register_taxonomy( 'weblex-importer-category', array( 'weblex-importer-post' ), $args );
	}


	/**
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	function pre_get_weblex_importer_posts( $query ) {
		if ( ( $query->is_main_query() ) && is_tax( 'weblex-importer-tag' ) ) {
			$query->set( 'post_type', 'weblex-importer-post' );
		}
	}
}
