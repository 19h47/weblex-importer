<?php

/**
 * The post type of the plugin.
 *
 * @link       https://github.com/19h47/weblex-rss-feed/
 * @since      0.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin
 */

class WebLex_RSS_Feed_Post {

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
	 * Updated messages
	 *
	 * @param array $messages Post updated messages. For defaults see $messages declarations above.
	 * @return array $message
	 * @link https://developer.wordpress.org/reference/hooks/post_updated_messages/
	 * @access public
	 */
	public function updated_messages( array $messages ) : array {
		global $post;

		$post_ID     = isset( $post_ID ) ? (int) $post_ID : 0;
		$preview_url = get_preview_post_link( $post );

		/* translators: Publish box date format, see https://secure.php.net/date */
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

		$view_link_html = sprintf(
			' <a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'View post', 'weblexrssfeed' )
		);

		$scheduled_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'Preview post', 'weblexrssfeed' )
		);

		$preview_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( $preview_url ),
			__( 'Preview post', 'weblexrssfeed' )
		);

		$messages['weblex-rss-feed-post'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Post updated.', 'weblexrssfeed' ) . $view_link_html,
			2  => __( 'Custom field updated.', 'weblexrssfeed' ),
			3  => __( 'Custom field deleted.', 'weblexrssfeed' ),
			4  => __( 'Post updated.', 'weblexrssfeed' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s.', 'weblexrssfeed' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore
			6  => __( 'Post published.', 'weblexrssfeed' ) . $view_link_html,
			7  => __( 'Post saved.', 'weblexrssfeed' ),
			8  => __( 'Post submitted.', 'weblexrssfeed' ) . $preview_link_html,
			9  => sprintf( __( 'Post scheduled for: %s.', 'weblexrssfeed' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_link_html, // phpcs:ignore
			10 => __( 'Post draft updated.', 'weblexrssfeed' ) . $preview_link_html,
		);

		return $messages;
	}


	/**
	 * Bulk updated messages
	 *
	 * @param array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
	 * @param array $bulk_counts Array of item counts for each message, used to build internationalized strings.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/bulk_post_updated_messages/
	 *
	 * @return array $bulk_counts
	 */
	public function bulk_updated_messages( array $bulk_messages, array $bulk_counts ) : array {
		$bulk_messages['weblex-rss-feed-post'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'], 'weblexrssfeed' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.', 'weblexrssfeed' ) :
				/* translators: %s: Number of posts. */
				_n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'], 'weblexrssfeed' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s post permanently deleted.', '%s post permanently deleted.', $bulk_counts['deleted'], 'weblexrssfeed' ),
			/* translators: %s: Number of posts.. */
			'trashed'   => _n( '%s post moved to the Trash.', '%s post moved to the Trash.', $bulk_counts['trashed'], 'weblexrssfeed' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s post restored from the Trash.', '%s post restored from the Trash.', $bulk_counts['untrashed'], 'weblexrssfeed' ),
		);

		return $bulk_messages;
	}


	/**
	 * Register Custom Post Type
	 *
	 * @return void
	 * @access public
	 */
	public function register() : void {
		$labels = array(
			'name'                     => _x( 'Posts', 'post type generale name', 'weblexrssfeed' ),
			'singular_name'            => _x( 'Post', 'post type singular name', 'weblexrssfeed' ),
			'add_new'                  => _x( 'Add New', 'post type', 'weblexrssfeed' ),
			'add_new_item'             => __( 'Add New Post', 'weblexrssfeed' ),
			'edit_item'                => __( 'Edit Post', 'weblexrssfeed' ),
			'new_item'                 => __( 'New Post', 'weblexrssfeed' ),
			'view_items'               => __( 'View Posts', 'weblexrssfeed' ),
			'view_item'                => __( 'View Post', 'weblexrssfeed' ),
			'search_items'             => __( 'Search Posts', 'weblexrssfeed' ),
			'not_found'                => __( 'No Posts found.', 'weblexrssfeed' ),
			'not_found_in_trash'       => __( 'No Posts found in Trash.', 'weblexrssfeed' ),
			'parent_item_colon'        => __( 'Parent Post:', 'weblexrssfeed' ),
			'all_items'                => __( 'All Posts', 'weblexrssfeed' ),
			'archives'                 => __( 'Post Archives', 'weblexrssfeed' ),
			'attributes'               => __( 'Post Attributes', 'weblexrssfeed' ),
			'insert_into_item'         => __( 'Insert into post', 'weblexrssfeed' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this post', 'weblexrssfeed' ),
			'featured_image'           => _x( 'Featured Image', 'post', 'weblexrssfeed' ),
			'set_featured_image'       => _x( 'Set featured image', 'post', 'weblexrssfeed' ),
			'remove_featured_image'    => _x( 'Remove featured image', 'post', 'weblexrssfeed' ),
			'use_featured_image'       => _x( 'Use as featured image', 'post', 'weblexrssfeed' ),
			'items_list_navigation'    => __( 'Posts list navigation', 'weblexrssfeed' ),
			'items_list'               => __( 'Posts list', 'weblexrssfeed' ),
			'item_published'           => __( 'post published.', 'weblexrssfeed' ),
			'item_published_privately' => __( 'post published privately.', 'weblexrssfeed' ),
			'item_reverted_to_draft'   => __( 'post reverted to draft.', 'weblexrssfeed' ),
			'item_scheduled'           => __( 'post scheduled.', 'weblexrssfeed' ),
			'item_updated'             => __( 'post updated.', 'weblexrssfeed' ),
		);

		$rewrite = array(
			'slug'       => 'weblex-rss-feed-post',
			'with_front' => true,
		);

		$args = array(
			'label'               => __( 'Post', 'weblexrssfeed' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-analytics',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
			'show_in_graphql'     => true,
			'taxonomies'          => array( 'weblex-rss-feed-tag' ),
		);

		register_post_type( 'weblex-rss-feed-post', $args );
	}
}
