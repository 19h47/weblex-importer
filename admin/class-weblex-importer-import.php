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
	public function update_options( $old_value, $value, $option ) : void {
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
	public function fetch( $url ) : void {
		$rss = fetch_feed( $url );

		if ( ! is_wp_error( $rss ) ) {
			$title    = $rss->get_title();
			$post_tag = $this->get_tag_by_name( sanitize_title( $title ) );

			foreach ( $rss->get_items( 0, $rss->get_item_quantity( 0 ) ) as $item ) {
				$item_id       = md5( serialize( $item->data ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				$item_pub_date = gmdate( $item->get_date( 'Y-m-d H:i:s' ) );

				$post_categories = $this->extract_categories( $item->get_categories() );
				$post_activities = $this->get_activities( $item );
				$post_keywords   = $this->get_keywords( $item );

				$post_type = weblex_importer_get_post_type();

				$tag      = 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag';
				$category = 'weblex-importer-post' === $post_type ? 'weblex-importer-category' : 'category';

				$query = new WP_Query(
					array(
						'post_type'              => $post_type,
						'meta_key'               => 'weblex-importer-id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						'meta_value'             => $item_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
						'no_found_rows'          => true,
						'update_post_term_cache' => false,
						'update_post_meta_cache' => false,
					)
				);

				if ( $query->have_posts() ) {
					$post = $query->next_post();

					if ( strtotime( $post->post_modified ) === strtotime( $item_pub_date ) ) {
						continue;
					}

					if ( strtotime( $post->post_modified ) < strtotime( $item_pub_date ) ) {

						if ( $item->get_description( false ) ) {
							$post->post_content = $item->get_description( false );
						}

						$post->post_title    = $item->get_title();
						$post->post_modified = $item_pub_date;
						$post->post_excerpt  = $this->get_introduction( $item );

						$updated_post_id = wp_update_post( $post );

						if ( 0 !== $updated_post_id ) {
							if ( $post_activities ) {
								wp_set_object_terms( $updated_post_id, $post_activities, 'weblex-importer-activity', false );
							}

							if ( $post_keywords ) {
								wp_set_object_terms( $updated_post_id, $post_keywords, 'weblex-importer-keyword', false );
							}

							wp_set_object_terms( $updated_post_id, $post_tag, $tag, false );
							wp_set_object_terms( $updated_post_id, $post_categories, $category, false );
						}

						if ( $this->get_image_url( $item ) ) {
							$this->set_post_thumbnail( $this->get_image_url( $item ), $updated_post_id );
						}
					}
				} else {
					$post = array(
						'post_title'   => $item->get_title(), // The title of the post.
						'post_type'    => $post_type,
						'post_status'  => 'publish',
						'post_date'    => $item_pub_date, // The time the post was made.
						'post_excerpt' => $this->get_introduction( $item ),
					);

					if ( $item->get_description( false ) ) {
						$post['post_content'] = $item->get_description( false );
					}

					$inserted_post_id = wp_insert_post( $post );

					if ( 0 !== $inserted_post_id ) {
						if ( $post_activities ) {
							wp_set_object_terms( $inserted_post_id, $post_activities, 'weblex-importer-activity', false );
						}

						if ( $post_keywords ) {
							wp_set_object_terms( $inserted_post_id, $post_keywords, 'weblex-importer-keyword', false );
						}

						wp_set_object_terms( $inserted_post_id, $post_tag, $tag, false );
						wp_set_object_terms( $inserted_post_id, $post_categories, $category, false );

						update_post_meta( $inserted_post_id, 'weblex-importer-id', $item_id );
					}

					if ( $this->get_image_url( $item ) ) {
						$this->set_post_thumbnail( $this->get_image_url( $item ), $inserted_post_id );
					}
				}
			}
		}
	}


	/**
	 * Handles extraction of tags from a list of RSS item categories.
	 *
	 * @param array $categories RSS item categories.
	 *
	 * @return array $tags
	 */
	private function extract_categories( $categories ) : array {
		$tags = array();

		foreach ( $categories as $category ) {
			array_push( $tags, $category->get_term() );
		}

		return $tags;
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
	private function get_tag_by_name( string $name ) : int {
		$post_type = weblex_importer_get_post_type();
		$term      = get_term_by( 'slug', $name, 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag' );

		if ( false === $term ) {
			$term = wp_insert_term( $name, 'weblex-importer-post' === $post_type ? 'weblex-importer-tag' : 'post_tag' );

			return (int) $term['term_id'];
		}

		return (int) $term->term_id;
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
	private function get_activities( $item ) : array {
		$activites = array();

		if ( ! $item->get_item_tags( '', 'activites' ) ) {
			return $activites;
		}

		if ( ! isset( $item->get_item_tags( '', 'activites' )[0] ) ) {
			return $activites;
		}

		return array_map(
			function( $activite ) {
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
	private function get_keywords( $item ) : array {
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
	private function get_introduction( $item ) : string {
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

		return $attachment_id;
	}
}
