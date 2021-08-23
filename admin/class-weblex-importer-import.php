<?php
/**
 * The settings of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.0.0
 *
 * @package    WebLex_Importer
 * @subpackage WebLex_Importer/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class WebLex_Importer_Import {

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
	 * Cron
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
	 * @param mixed $old_value The old option value.
	 * @param mixed $value The new option value.
	 * @param string $option Option name.
	 *
	 * @return void
	 */
	public function update_options( $old_value, $value, $option ) : void {
		foreach ( $value as $key => $v ) {
			if ( '' !== $v['url'] ) {
				$this->fetch( $v['url'] );
			}
		}
	}


	/**
	 * Fetch
	 *
	 * @param string $url
	 *
	 * @return void
	 */
	public function fetch( $url ) : void {
		$rss = fetch_feed( $url );

		if ( ! is_wp_error( $rss ) ) {
			$title   = $rss->get_title();
			$term_id = $this->get_tag_by_name( $title );

			foreach ( $rss->get_items( 0, $rss->get_item_quantity( 0 ) ) as $item ) {
				$item_id       = $item->get_id( false );
				$item_pub_date = gmdate( $item->get_date( 'Y-m-d H:i:s' ) );

				$post_tags = $this->extract_categories( $item->get_categories() );

				$query = new WP_Query(
					array(
						'post_type'  => 'weblex-importer-post',
						'meta_key'   => 'weblex-importer-id',
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

						if ( 0 !== $updated_post_id ) {
							wp_set_object_terms( $updated_post_id, $term_id, 'weblex-importer-tag', false );
							wp_set_object_terms( $updated_post_id, $post_tags, 'weblex-importer-category', false );
						}
					}
				} else {
					$post = array(
						'post_type'    => 'weblex-importer-post',
						'post_content' => $item->get_description( false ), // The full text of the post.
						'post_title'   => $item->get_title(), // The title of the post.
						'post_status'  => 'publish',
						'post_date'    => $item_pub_date, // The time the post was made.
						'tags_input'   => $post_tags,
					);

					$inserted_post_id = wp_insert_post( $post );

					if ( 0 !== $inserted_post_id ) {
						wp_set_object_terms( $inserted_post_id, $term_id, 'weblex-importer-tag', false );
						wp_set_object_terms( $inserted_post_id, $post_tags, 'weblex-importer-category', false );

						update_post_meta( $inserted_post_id, 'weblex-importer-id', $item_id );
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
			array_push( $tags, sanitize_title( $category->get_term() ) );
		}

		return $tags;
	}


	/**
	 * Get tag by name
	 *
	 * @param string $name Title.
	 *
	 * @since    0.0.0
	 * @return int
	 */
	private function get_tag_by_name( string $name ) : int {

		$term = get_term_by( 'name', $name, 'weblex-importer-tag' );

		if ( false === $term ) {
			$term = wp_insert_term( $name, 'weblex-importer-tag' );
		}

		return (int) $term->term_id;
	}
}
