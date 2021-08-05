<?php

/**
 * The settings of the plugin.
 *
 * @link       https://github.com/19h47/weblex-rss-feed/
 * @since      0.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class WebLex_RSS_Feed_Insert_Post {

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
	 * Init
	 *
	 * @param mixed $old_value The old option value.
	 * @param mixed $value The new option value.
	 * @param string $option Option name.
	 */
	public function init( $old_value, $value, $option ) {
		$diff = array_udiff(
			$old_value,
			$value,
			function ( $a, $b ) {
				return strtotime( $a['date'] ) < strtotime( $b['date'] );
			}
		);

		foreach ( $diff as $key => $value ) {
			if ( '' !== $value['url'] ) {

				$rss = fetch_feed( $value['url'] );

				foreach ( $rss->get_items() as $item ) {
					$item_id       = $item->get_id( false );
					$item_pub_date = gmdate( $item->get_date( 'Y-m-d H:i:s' ) );

					$item_categories = $item->get_categories();
					$post_tags       = $this->extract_tags( $item_categories );

					$query = new WP_Query(
						array(
							'post_type'  => 'weblex-rss-feed-post',
							'meta_key'   => 'weblex-rss-feed-id',
							'meta_value' => $item_id,
						)
					);

					if ( $query->have_posts() ) {
						$post = $query->next_post();

						if ( strtotime( $post->post_modified ) < strtotime( $item_pub_date ) ) {
							$post->post_content  = $item->get_description( false );
							$post->post_title    = $item->get_title();
							$post->post_modified = $item_pub_date;

							$updated_post_id = wp_update_post( $post );

							if ( $updated_post_id != 0 ) {
								wp_set_object_terms( $updated_post_id, $post_cat_id, 'category', false );
								wp_set_post_tags( $updated_post_id, $post_tags, false );

								// if ( $this->is_image_import() ) {
									//Image importing routines
									// $post_data = array(
									// 	'post_content' => $post->post_content,
									// 	'post_date'    => $post->post_modified,
									// );

									// $processed_post_content = $this->process_image_tags( $post_data, $updated_post_id );

									// //Update post content
									// if ( ! is_wp_error( $processed_post_content ) ) {
									// 	$this->update_post_content( $processed_post_content, $updated_post_id );
									// }
								// }
							}
						}
					} else {
						$post = array(
							'post_type'    => 'weblex-rss-feed-post',
							'post_content' => $item->get_description( false ), // The full text of the post.
							'post_title'   => $item->get_title(), // The title of the post.
							'post_status'  => 'publish',
							'post_date'    => $item_pub_date, // The time the post was made.
							'tags_input'   => $post_tags,
						);

						$inserted_post_id = wp_insert_post( $post );

						if ( $inserted_post_id != 0 ) {
							// wp_set_object_terms( $inserted_post_id, $post_cat_id, 'category', false );
							update_post_meta( $inserted_post_id, 'weblex-rss-feed-id', $item_id );
						}
					}
				}
			}
		}
	}


	/**
	* Handles extraction of post tags from a list of RSS item categories.
	*
	*/
	private function extract_tags( $rss_item_cats ) {

		$post_tags = array();

		foreach ( $rss_item_cats as $category ) {

			$raw_tag = $category->get_term();

			array_push( $post_tags, sanitize_title( $raw_tag ) );

		}

		return $post_tags;
	}
}
