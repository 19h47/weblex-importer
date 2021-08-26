<?php

/**
 * The post type of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin
 */

class WebLex_Importer_Post {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
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

		unset( $columns['date'] );

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = __( 'Thumbnail', 'weblex-importer' );
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
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

		$view_link_html = sprintf(
			' <a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'View post', 'weblex-importer' )
		);

		$scheduled_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'Preview post', 'weblex-importer' )
		);

		$preview_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( $preview_url ),
			__( 'Preview post', 'weblex-importer' )
		);

		$messages[ $this->post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Post updated.', 'weblex-importer' ) . $view_link_html,
			2  => __( 'Custom field updated.', 'weblex-importer' ),
			3  => __( 'Custom field deleted.', 'weblex-importer' ),
			4  => __( 'Post updated.', 'weblex-importer' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s.', 'weblex-importer' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore
			6  => __( 'Post published.', 'weblex-importer' ) . $view_link_html,
			7  => __( 'Post saved.', 'weblex-importer' ),
			8  => __( 'Post submitted.', 'weblex-importer' ) . $preview_link_html,
			9  => sprintf( __( 'Post scheduled for: %s.', 'weblex-importer' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_link_html, // phpcs:ignore
			10 => __( 'Post draft updated.', 'weblex-importer' ) . $preview_link_html,
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
			'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'], 'weblex-importer' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.', 'weblex-importer' ) :
				/* translators: %s: Number of posts. */
				_n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'], 'weblex-importer' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s post permanently deleted.', '%s post permanently deleted.', $bulk_counts['deleted'], 'weblex-importer' ),
			/* translators: %s: Number of posts.. */
			'trashed'   => _n( '%s post moved to the Trash.', '%s post moved to the Trash.', $bulk_counts['trashed'], 'weblex-importer' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s post restored from the Trash.', '%s post restored from the Trash.', $bulk_counts['untrashed'], 'weblex-importer' ),
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
			'name'                     => _x( 'Posts', 'weblex-importer-post type generale name', 'weblex-importer' ),
			'singular_name'            => _x( 'Post', 'weblex-importer-post type singular name', 'weblex-importer' ),
			'add_new'                  => _x( 'Add New', 'weblex-importer-post type', 'weblex-importer' ),
			'add_new_item'             => __( 'Add New Post', 'weblex-importer' ),
			'edit_item'                => __( 'Edit Post', 'weblex-importer' ),
			'new_item'                 => __( 'New Post', 'weblex-importer' ),
			'view_items'               => __( 'View Posts', 'weblex-importer' ),
			'view_item'                => __( 'View Post', 'weblex-importer' ),
			'search_items'             => __( 'Search Posts', 'weblex-importer' ),
			'not_found'                => __( 'No Posts found.', 'weblex-importer' ),
			'not_found_in_trash'       => __( 'No Posts found in Trash.', 'weblex-importer' ),
			'parent_item_colon'        => __( 'Parent Post:', 'weblex-importer' ),
			'all_items'                => __( 'All Posts', 'weblex-importer' ),
			'archives'                 => __( 'Post Archives', 'weblex-importer' ),
			'attributes'               => __( 'Post Attributes', 'weblex-importer' ),
			'insert_into_item'         => __( 'Insert into post', 'weblex-importer' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this post', 'weblex-importer' ),
			'featured_image'           => _x( 'Featured Image', 'post', 'weblex-importer' ),
			'set_featured_image'       => _x( 'Set featured image', 'post', 'weblex-importer' ),
			'remove_featured_image'    => _x( 'Remove featured image', 'post', 'weblex-importer' ),
			'use_featured_image'       => _x( 'Use as featured image', 'post', 'weblex-importer' ),
			'items_list_navigation'    => __( 'Posts list navigation', 'weblex-importer' ),
			'items_list'               => __( 'Posts list', 'weblex-importer' ),
			'item_published'           => __( 'post published.', 'weblex-importer' ),
			'item_published_privately' => __( 'post published privately.', 'weblex-importer' ),
			'item_reverted_to_draft'   => __( 'post reverted to draft.', 'weblex-importer' ),
			'item_scheduled'           => __( 'post scheduled.', 'weblex-importer' ),
			'item_updated'             => __( 'post updated.', 'weblex-importer' ),
		);

		$rewrite = array(
			'slug'       => 'les-infos-du-jour',
			'with_front' => true,
		);

		$args = array(
			'label'               => __( 'Post', 'weblex-importer' ),
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
			'taxonomies'          => array( 'weblex-importer-tag', 'weblex-importer-category' ),
		);

		register_post_type( $this->post_type, $args );
	}
}
