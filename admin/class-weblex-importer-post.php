<?php
/**
 * The post type of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin
 */

/**
 * Weblex Importer Post
 */
class Weblex_Importer_Post {

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
	 * The post type name
	 *
	 * @since 0.0.5
	 * @access private
	 * @var string
	 */
	private $post_type;


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
		$this->post_type   = 'weblex-importer-post';

	}



	/**
	 * CSS
	 *
	 * @return bool
	 */
	public function css() : bool {
		global $typenow;

		if ( $this->post_type !== $typenow ) {
			return false;
		}

		?>
		<style>
			.fixed .column-thumbnail {
				vertical-align: top;
				width: 80px;
			}

			.fixed .column-thumbnail a {
				display: block;
			}
			.fixed .column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;
				width: 80px;
				height: 80px;
				object-fit: contain;
				object-position: center;
				overflow: hidden;
			}
		</style>
		<?php

		return true;
	}


	/**
	 * Add custom columns
	 *
	 * @param array $columns Array of columns.
	 * @return array $new_columns
	 * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
	 */
	public function add_custom_columns( array $columns ) : array {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = __( 'Thumbnail', 'webleximporter' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param string $column_name The column name.
	 * @param int    $post_id The ID of the post.
	 * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
	 *
	 * @return void
	 */
	public function render_custom_columns( string $column_name, int $post_id ) : void {
		switch ( $column_name ) {
			case 'thumbnail':
				$thumbnail = get_the_post_thumbnail( $post_id, 'medium' );
				$html      = '—';

				if ( $thumbnail ) {
					$html  = '<a href="' . esc_attr( get_edit_post_link( $post_id ) ) . '">';
					$html .= $thumbnail;
					$html .= '</a>';
				}

				echo wp_kses_post( $html );

				break;
		}
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
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i', 'webleximporter' ), strtotime( $post->post_date ) );

		$view_link_html = sprintf(
			' <a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'View post', 'webleximporter' )
		);

		$scheduled_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'Preview post', 'webleximporter' )
		);

		$preview_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( $preview_url ),
			__( 'Preview post', 'webleximporter' )
		);

		$messages[ $this->post_type ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Post updated.', 'webleximporter' ) . $view_link_html,
			2 => __( 'Custom field updated.', 'webleximporter' ),
			3 => __( 'Custom field deleted.', 'webleximporter' ),
			4 => __( 'Post updated.', 'webleximporter' ),
			/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s.', 'webleximporter' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore
		6     => __( 'Post published.', 'webleximporter' ) . $view_link_html,
		7     => __( 'Post saved.', 'webleximporter' ),
		8     => __( 'Post submitted.', 'webleximporter' ) . $preview_link_html,
		9  => sprintf( __( 'Post scheduled for: %s.', 'webleximporter' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_link_html, // phpcs:ignore
		10    => __( 'Post draft updated.', 'webleximporter' ) . $preview_link_html,
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
		$bulk_messages[ $this->post_type ] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'], 'webleximporter' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.', 'webleximporter' ) :
				/* translators: %s: Number of posts. */
				_n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'], 'webleximporter' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s post permanently deleted.', '%s post permanently deleted.', $bulk_counts['deleted'], 'webleximporter' ),
			/* translators: %s: Number of posts.. */
			'trashed'   => _n( '%s post moved to the Trash.', '%s post moved to the Trash.', $bulk_counts['trashed'], 'webleximporter' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s post restored from the Trash.', '%s post restored from the Trash.', $bulk_counts['untrashed'], 'webleximporter' ),
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
			'name'                     => _x( 'Posts', 'weblex-importer-post type generale name', 'webleximporter' ),
			'singular_name'            => _x( 'Post', 'weblex-importer-post type singular name', 'webleximporter' ),
			'add_new'                  => _x( 'Add New', 'weblex-importer-post type', 'webleximporter' ),
			'add_new_item'             => __( 'Add New Post', 'webleximporter' ),
			'edit_item'                => __( 'Edit Post', 'webleximporter' ),
			'new_item'                 => __( 'New Post', 'webleximporter' ),
			'view_items'               => __( 'View Posts', 'webleximporter' ),
			'view_item'                => __( 'View Post', 'webleximporter' ),
			'search_items'             => __( 'Search Posts', 'webleximporter' ),
			'not_found'                => __( 'No Posts found.', 'webleximporter' ),
			'not_found_in_trash'       => __( 'No Posts found in Trash.', 'webleximporter' ),
			'parent_item_colon'        => __( 'Parent Post:', 'webleximporter' ),
			'all_items'                => __( 'All Posts', 'webleximporter' ),
			'archives'                 => __( 'Post Archives', 'webleximporter' ),
			'attributes'               => __( 'Post Attributes', 'webleximporter' ),
			'insert_into_item'         => __( 'Insert into post', 'webleximporter' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this post', 'webleximporter' ),
			'featured_image'           => _x( 'Featured Image', 'post', 'webleximporter' ),
			'set_featured_image'       => _x( 'Set featured image', 'post', 'webleximporter' ),
			'remove_featured_image'    => _x( 'Remove featured image', 'post', 'webleximporter' ),
			'use_featured_image'       => _x( 'Use as featured image', 'post', 'webleximporter' ),
			'items_list_navigation'    => __( 'Posts list navigation', 'webleximporter' ),
			'items_list'               => __( 'Posts list', 'webleximporter' ),
			'item_published'           => __( 'post published.', 'webleximporter' ),
			'item_published_privately' => __( 'post published privately.', 'webleximporter' ),
			'item_reverted_to_draft'   => __( 'post reverted to draft.', 'webleximporter' ),
			'item_scheduled'           => __( 'post scheduled.', 'webleximporter' ),
			'item_updated'             => __( 'post updated.', 'webleximporter' ),
		);

		$rewrite = array(
			'slug'       => 'les-infos-du-jour',
			'with_front' => true,
		);

		$args = array(
			'label'               => __( 'Post', 'webleximporter' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
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
			'taxonomies'          => array( 'weblex-importer-activity', 'weblex-importer-category', 'weblex-importer-keyword', 'weblex-importer-tag' ),
		);

		register_post_type( $this->post_type, $args );
	}


	/**
	 * Delete attachment
	 *
	 * @param int     $postid Post ID.
	 * @param WP_Post $post Post object.
	 */
	public function delete_attachment( int $postid, WP_Post $post ) {
		$post_type = get_post_type( $postid );

		if ( has_post_thumbnail( $postid ) && $post_type === $this->post_type ) {
			$attachment_id = get_post_thumbnail_id( $postid );

			wp_delete_attachment( $attachment_id, true );
		}
	}
}
