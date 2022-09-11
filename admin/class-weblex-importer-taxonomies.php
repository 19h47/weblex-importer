<?php
/**
 * Taxonomies of the plugin.
 *
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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
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

		$this->register_activity();
		$this->register_category();
		$this->register_tag();
	}



	/**
	 * Register Activity
	 *
	 * @return void
	 */
	public function register_activity() : void {
		$labels = array(
			'name'                       => _x( 'Activities', 'post activity general name', 'webleximporter' ),
			'singular_name'              => _x( 'Activity', 'post activity singular name', 'webleximporter' ),
			'search_items'               => __( 'Search Activities', 'webleximporter' ),
			'all_items'                  => __( 'All Activities', 'webleximporter' ),
			'popular_items'              => __( 'Popular Activities', 'webleximporter' ),
			'edit_item'                  => __( 'Edit Activity', 'webleximporter' ),
			'view_item'                  => __( 'View Activity', 'webleximporter' ),
			'update_item'                => __( 'Update Activity', 'webleximporter' ),
			'add_new_item'               => __( 'Add New Activity', 'webleximporter' ),
			'new_item_name'              => __( 'New Activity Name', 'webleximporter' ),
			'separate_items_with_commas' => __( 'Separate activities with commas', 'webleximporter' ),
			'add_or_remove_items'        => __( 'Add or remove activities', 'webleximporter' ),
			'choose_from_most_used'      => __( 'Choose from the most used activities', 'webleximporter' ),
			'not_found'                  => __( 'No activities found.', 'webleximporter' ),
			'no_terms'                   => __( 'No activities', 'webleximporter' ),
			'items_list_navigation'      => __( 'Activities list navigation', 'webleximporter' ),
			'items_list'                 => __( 'Activities list', 'webleximporter' ),
			/* translators: Post activity heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'post activity', 'webleximporter' ),
			'back_to_items'              => __( '&larr; Back to Activities', 'webleximporter' ),
		);

		$rewrite = array(
			'slug'         => 'les-infos-du-jour-activities',
			'with_front'   => true,
			'hierarchical' => false,
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
			'rewrite'            => $rewrite,
		);

		register_taxonomy( 'weblex-importer-activity', array( 'weblex-importer-post' ), $args );
	}


	/**
	 * Register category
	 *
	 * @return void
	 */
	public function register_category() {
		$labels = array(
			'name'                       => _x( 'Categories', 'post category general name', 'webleximporter' ),
			'singular_name'              => _x( 'Category', 'post category singular name', 'webleximporter' ),
			'search_items'               => __( 'Search Categories', 'webleximporter' ),
			'all_items'                  => __( 'All Categories', 'webleximporter' ),
			'popular_items'              => __( 'Popular Categories', 'webleximporter' ),
			'edit_item'                  => __( 'Edit Category', 'webleximporter' ),
			'view_item'                  => __( 'View Category', 'webleximporter' ),
			'update_item'                => __( 'Update Category', 'webleximporter' ),
			'add_new_item'               => __( 'Add New Category', 'webleximporter' ),
			'new_item_name'              => __( 'New Category Name', 'webleximporter' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'webleximporter' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'webleximporter' ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', 'webleximporter' ),
			'not_found'                  => __( 'No categories found.', 'webleximporter' ),
			'no_terms'                   => __( 'No categories', 'webleximporter' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'webleximporter' ),
			'items_list'                 => __( 'Categories list', 'webleximporter' ),
			/* translators: Post category heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'post category', 'webleximporter' ),
			'back_to_items'              => __( '&larr; Back to Categories', 'webleximporter' ),
		);

		$rewrite = array(
			'slug'         => 'les-infos-du-jour-categories',
			'with_front'   => true,
			'hierarchical' => false,
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
			'rewrite'            => $rewrite,
		);

		register_taxonomy( 'weblex-importer-category', array( 'weblex-importer-post' ), $args );
	}

	/**
	 * Register tag
	 *
	 * @return void
	 */
	public function register_tag() {
		$labels = array(
			'name'                       => _x( 'Tags', 'post tag general name', 'webleximporter' ),
			'singular_name'              => _x( 'Tag', 'post tag singular name', 'webleximporter' ),
			'search_items'               => __( 'Search Tags', 'webleximporter' ),
			'all_items'                  => __( 'All Tags', 'webleximporter' ),
			'popular_items'              => __( 'Popular Tags', 'webleximporter' ),
			'edit_item'                  => __( 'Edit Tag', 'webleximporter' ),
			'view_item'                  => __( 'View Tag', 'webleximporter' ),
			'update_item'                => __( 'Update Tag', 'webleximporter' ),
			'add_new_item'               => __( 'Add New Tag', 'webleximporter' ),
			'new_item_name'              => __( 'New Tag Name', 'webleximporter' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'webleximporter' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'webleximporter' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'webleximporter' ),
			'not_found'                  => __( 'No tags found.', 'webleximporter' ),
			'no_terms'                   => __( 'No tags', 'webleximporter' ),
			'items_list_navigation'      => __( 'Tags list navigation', 'webleximporter' ),
			'items_list'                 => __( 'Tags list', 'webleximporter' ),
			/* translators: Post tag heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'post tag', 'webleximporter' ),
			'back_to_items'              => __( '&larr; Back to Tags', 'webleximporter' ),
		);

		$rewrite = array(
			'slug'         => 'les-infos-du-jour-tag',
			'with_front'   => true,
			'hierarchical' => false,
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
			'rewrite'            => $rewrite,
		);

		register_taxonomy( 'weblex-importer-tag', array( 'weblex-importer-post' ), $args );
	}




	/**
	 * Pre get WebLex importer posts
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	public function pre_get_weblex_importer_posts( WP_Query $query ) {
		if ( ( $query->is_main_query() ) && is_tax( 'weblex-importer-tag' ) ) {
			$query->set( 'post_type', 'weblex-importer-post' );
		}

		if ( ( $query->is_main_query() ) && is_tax( 'weblex-importer-category' ) ) {
			$query->set( 'post_type', 'weblex-importer-post' );
		}
	}
}
