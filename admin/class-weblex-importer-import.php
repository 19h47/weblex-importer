<?php
/**
 * Import.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin
 */

/**
 * Class Weblex_Importer_Import
 */
class Weblex_Importer_Import {

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
	 * XML Namespaces
	 *
	 * @since  0.2.0
	 * @access private
	 * @var    string $xml_namesapce The URL of the XML namespace.
	 */
	private $xml_namespace = 'http://www.w3.org/2005/Atom';


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
	 * Cron
	 *
	 * @access public
	 */
	public function cron_update_options() {
		$options = get_option( 'weblex_importer_options' );

		foreach ( $options as $key => $value ) {
			if ( '' !== $value['url'] ) {
				$this->fetch( $value['url'] );
			}
		}
	}


	/**
	 * Update options.
	 *
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value The new option value.
	 * @param string $option Option name.
	 *
	 * @return void
	 */
	public function update_options( $old_value, $value, $option ): void {
		unset( $option );

		if ( $value['post'] !== $old_value['post'] ) {
			return;
		}

		foreach ( $value as $key => $v ) {
			if ( '' !== $v['url'] && $old_value[ $key ]['date'] !== $v['date'] ) {
				$this->fetch( $v['url'] );
			}
		}
	}


	/**
	 * Fetch
	 *
	 * @param string $url URL.
	 *
	 * @see https://developer.wordpress.org/reference/functions/fetch_feed/
	 * @see https://simplepie.org/api/class-SimplePie_Item.html#_get_id
	 *
	 * @return void
	 */
	public function fetch( $url ): void {
		$post_type = weblex_importer_get_post_type();
		$rss       = fetch_feed( $url );

		if ( ! is_wp_error( $rss ) ) {
			$quantity = 'weblex-importer-post' === $post_type ? $rss->get_item_quantity( 0 ) : 400;

			$title    = $rss->get_title();
			$post_tag = $this->get_tag_by_name( $title );

			foreach ( $rss->get_items( 0, $quantity ) as $item ) {
				// The link identifies the item, the hash tells whether its content changed.
				// Hash first: SimplePie getters cache their result in $item->data.
				$item_hash     = md5( serialize( $item->data ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				$item_link     = (string) $item->get_permalink();
				$item_pub_date = gmdate( $item->get_date( 'Y-m-d H:i:s' ) );

				// An item without link is found by its hash only, as before 1.1.0.
				$post = '' !== $item_link ? $this->get_post_by_meta( 'weblex-importer-link', $item_link ) : null;

				// Posts imported before 1.1.0 only have the hash: find them by it, then attach the link.
				if ( null === $post ) {
					$post = $this->get_post_by_meta( 'weblex-importer-id', $item_hash );

					if ( null !== $post && '' !== $item_link ) {
						update_post_meta( $post->ID, 'weblex-importer-link', $item_link );
					}
				}

				if ( null !== $post && get_post_meta( $post->ID, 'weblex-importer-id', true ) === $item_hash ) {
					continue;
				}

				$post_categories = $this->extract_categories( $item->get_categories() );
				$post_activities = $this->get_activities( $item );
				$post_keywords   = $this->get_keywords( $item );

				$tag      = 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag';
				$category = 'weblex-importer-post' === $post_type ? 'weblex-importer-category' : 'category';

				$postarr = array(
					'post_title'   => $item->get_title(),
					'post_excerpt' => $this->get_introduction( $item ),
				);

				if ( $item->get_description( false ) ) {
					$postarr['post_content'] = $item->get_description( false );
				}

				// An updated post keeps its date, so its URL stays the same.
				if ( null !== $post ) {
					$postarr['ID'] = $post->ID;

					$post_id = wp_update_post( $postarr );
				} else {
					$postarr['post_type']   = $post_type;
					$postarr['post_status'] = 'publish';
					$postarr['post_date']   = $item_pub_date;

					$post_id = wp_insert_post( $postarr );
				}

				if ( 0 === $post_id ) {
					continue;
				}

				if ( $post_activities ) {
					wp_set_object_terms( $post_id, $post_activities, 'weblex-importer-activity', false );
				}

				if ( $post_keywords ) {
					wp_set_object_terms( $post_id, $post_keywords, 'weblex-importer-keyword', false );
				}

				wp_set_object_terms( $post_id, $post_tag, $tag, false );
				wp_set_object_terms( $post_id, $post_categories, $category, false );

				update_post_meta( $post_id, 'weblex-importer-id', $item_hash );

				if ( '' !== $item_link ) {
					update_post_meta( $post_id, 'weblex-importer-link', $item_link );
				}

				$image_url = $this->get_image_url( $item );

				if ( $image_url ) {
					$this->set_post_thumbnail( $image_url, $post_id );
				}
			}
		}
	}


	/**
	 * Get post by meta
	 *
	 * Queries the database directly: other plugins filter WP_Query, WPML by language for instance,
	 * and made it return posts that did not match (#3). Whatever its status, a post is found:
	 * one a site moved to the trash is not imported again.
	 *
	 * @param string $key Meta key.
	 * @param string $value Meta value.
	 *
	 * @since 1.1.0
	 *
	 * @return WP_Post|null
	 */
	private function get_post_by_meta( string $key, string $value ): ?WP_Post {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- A lookup the next import must not find in a cache.
		$post_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta m ON m.post_id = p.ID WHERE p.post_type = %s AND m.meta_key = %s AND m.meta_value = %s LIMIT 1",
				weblex_importer_get_post_type(),
				$key,
				$value
			)
		);

		return $post_id ? get_post( (int) $post_id ) : null;
	}


	/**
	 * Handles extraction of tags from a list of RSS item categories.
	 *
	 * @param array $categories RSS item categories.
	 *
	 * @return array $tags
	 */
	private function extract_categories( $categories ): array {
		$post_type = weblex_importer_get_post_type();
		$terms     = array();

		foreach ( $categories as $category ) {
			$term = get_term_by( 'name', $category->get_term(), 'weblex-importer-post' === $post_type ? 'weblex-importer-category' : 'category', ARRAY_A );

			if ( false === $term ) {
				$term = wp_insert_term( $category->get_term(), 'weblex-importer-post' === $post_type ? 'weblex-importer-category' : 'category' );
			}

			array_push( $terms, $term['term_id'] );
		}

		return $terms;
	}


	/**
	 * Get tag by name
	 *
	 * @param string $name Term name.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_term_by/
	 *
	 * @since    0.0.0
	 * @return int
	 */
	private function get_tag_by_name( string $name ): int {
		$post_type = weblex_importer_get_post_type();
		$term      = get_term_by( 'slug', $name, 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag', ARRAY_A );

		if ( false === $term ) {
			$term = wp_insert_term( $name, 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag' );
		}

		return (int) $term['term_id'];
	}


	/**
	 * Image url
	 *
	 * @param object $item Item.
	 *
	 * @since 0.0.18
	 */
	private function get_image_url( $item ) {
		$image_url = null;

		if ( ! $item->get_item_tags( '', 'image' ) ) {
			return $image_url;
		}

		if ( ! isset( $item->get_item_tags( '', 'image' )[0] ) ) {
			return $image_url;
		}

		return $item->get_item_tags( '', 'image' )[0]['child']['']['url'][0]['data'];
	}


	/**
	 * Get activities
	 *
	 * @param object $item Item.
	 *
	 * @since 0.1.9
	 *
	 * @return array
	 */
	private function get_activities( $item ): array {
		$activites = array();

		if ( ! $item->get_item_tags( '', 'activites' ) ) {
			return $activites;
		}

		if ( ! isset( $item->get_item_tags( '', 'activites' )[0] ) ) {
			return $activites;
		}

		if ( is_null( $item->get_item_tags( '', 'activites' )[0]['child']['']['activite'] ) ) {
			return $activites;
		}

		return array_map(
			function ( $activite ) {
				return $activite['data'];
			},
			$item->get_item_tags( '', 'activites' )[0]['child']['']['activite']
		);
	}


	/**
	 * Get keywords
	 *
	 * @param object $item Item.
	 *
	 * @since 0.1.9
	 *
	 * @return array
	 */
	private function get_keywords( $item ): array {
		$keywords = array();

		if ( ! $item->get_item_tags( '', 'motsCles' ) ) {
			return $keywords;
		}

		if ( ! isset( $item->get_item_tags( '', 'motsCles' )[0] ) ) {
			return $keywords;
		}

		if ( ! isset( $item->get_item_tags( '', 'motsCles' )[0]['data'] ) ) {
			return $keywords;
		}

		return explode( ';', $item->get_item_tags( '', 'motsCles' )[0]['data'] );
	}


	/**
	 * Introduction
	 *
	 * @param object $item Item.
	 *
	 * @since 0.0.18
	 *
	 * @return string
	 */
	private function get_introduction( $item ): string {
		$introduction = '';

		if ( ! $item->get_item_tags( '', 'introduction' ) ) {
			return $introduction;
		}

		if ( ! isset( $item->get_item_tags( '', 'introduction' )[0] ) ) {
			return $introduction;
		}

		if ( ! isset( $item->get_item_tags( '', 'introduction' )[0]['data'] ) ) {
			return $introduction;
		}

		return $item->get_item_tags( '', 'introduction' )[0]['data'];
	}


	/**
	 * Set post thumbnail
	 *
	 * @param string      $url URL.
	 * @param int|WP_Post $post         Post ID or post object where thumbnail should be attached.
	 *
	 * @return int|WP_Error The ID of the attachment or a WP_Error on failure.
	 */
	public function set_post_thumbnail( string $url, $post ) {
		$post = get_post( $post );

		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		if ( null === $post ) {
			return new WP_Error( 'insert_attachment_failed', __( 'Invalid post', 'webleximporter' ) );
		}

		if ( empty( $url ) ) {
			return new WP_Error( 'insert_attachment_failed', __( 'Insert URL', 'webleximporter' ) );
		}

		$url = esc_url( $url );

		// Set variables for storage, fix file filename for query strings.
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $url, $matches );

		if ( ! $matches ) {
			return new WP_Error( 'insert_attachment_failed', __( 'Invalid image URL', 'webleximporter' ) );
		}

		// Array that represents a `$_FILES` upload array.
		$file_array         = array();
		$file_array['name'] = basename( $matches[0] );

		// @see https://developer.wordpress.org/reference/functions/download_url/
		$file_array['tmp_name'] = download_url( $url );

		if ( is_wp_error( $file_array['tmp_name'] ) ) {
			return $file_array['tmp_name'];
		}

		$previous_attachment_id = get_post_thumbnail_id( $post );

		// @see https://developer.wordpress.org/reference/functions/media_handle_sideload/
		$attachment_id = media_handle_sideload( $file_array, $post->ID );

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		// @see https://developer.wordpress.org/reference/functions/set_post_thumbnail/
		$post_thumbnail = set_post_thumbnail( $post->ID, $attachment_id );

		if ( false === $post_thumbnail ) {
			return new WP_Error( 'insert_attachment_failed', __( 'Problem to set post thumbnail', 'webleximporter' ) );
		}

		// Only the image imported for this post is deleted, not one picked from the media library.
		if ( $previous_attachment_id && wp_get_post_parent_id( $previous_attachment_id ) === $post->ID ) {
			wp_delete_attachment( $previous_attachment_id, true );
		}

		return $attachment_id;
	}
}
