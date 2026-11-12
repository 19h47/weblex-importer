<?php
/**
 * Tests of the import of a feed.
 *
 * @package Weblex_Importer
 */

/**
 * Imports the feeds of tests/fixtures, served without the network, as the cron of the plugin does.
 */
class Test_Import extends WP_UnitTestCase {

	/**
	 * URL of the feed, then of the same feed once Weblex edited an item.
	 */
	const FEED_URL        = 'https://www.weblex.fr/weblex/feed/1';
	const EDITED_FEED_URL = 'https://www.weblex.fr/weblex/feed/1?edited';

	/**
	 * Serve the fixtures, parsed again on each fetch.
	 *
	 * @return void
	 */
	public function set_up(): void {
		parent::set_up();

		add_filter( 'pre_http_request', array( $this, 'serve_fixture' ), 10, 3 );
		add_action( 'wp_feed_options', array( $this, 'disable_feed_cache' ) );
	}

	/**
	 * An item Weblex edits updates its post, at the same URL, instead of adding a second one (#4).
	 *
	 * @return void
	 */
	public function test_edited_item_updates_its_post(): void {
		$this->import( self::FEED_URL );

		$post = $this->get_post( 'grands-rouleurs-la-nouvelle-aide-est-precisee' );

		$this->import( self::EDITED_FEED_URL );

		$this->assertCount( 2, $this->get_posts() );

		$updated = get_post( $post->ID );

		$this->assertStringContainsString( 'précisée par décret', $updated->post_content );
		$this->assertSame( $post->post_date, $updated->post_date );
		$this->assertSame( $post->post_name, $updated->post_name );
	}

	/**
	 * A post imported before 1.1.0 has the hash of its item only: it is attached to its link, not imported again.
	 *
	 * @return void
	 */
	public function test_post_imported_before_link_is_not_imported_again(): void {
		$items   = fetch_feed( self::FEED_URL )->get_items();
		$post_id = self::factory()->post->create( array( 'post_type' => 'weblex-importer-post' ) );

		update_post_meta( $post_id, 'weblex-importer-id', md5( serialize( $items[0]->data ) ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize

		$this->import( self::FEED_URL );

		$this->assertCount( 2, $this->get_posts() );
		$this->assertSame( $items[0]->get_permalink(), get_post_meta( $post_id, 'weblex-importer-link', true ) );
	}

	/**
	 * A plugin filtering WP_Query, as WPML does, does not make an item look imported already (#3).
	 *
	 * @return void
	 */
	public function test_query_filters_do_not_change_the_post_found(): void {
		$other_post_id = self::factory()->post->create( array( 'post_type' => 'weblex-importer-post' ) );

		add_filter(
			'posts_pre_query',
			function ( $posts, WP_Query $query ) use ( $other_post_id ) {
				return 'weblex-importer-post' === $query->get( 'post_type' ) ? array( get_post( $other_post_id ) ) : $posts;
			},
			10,
			2
		);

		$this->import( self::FEED_URL );

		remove_all_filters( 'posts_pre_query' );

		$this->assertCount( 3, $this->get_posts() );
		$this->assertSame( '', get_post_meta( $other_post_id, 'weblex-importer-link', true ) );
	}

	/**
	 * Answer a request for a feed with its fixture.
	 *
	 * @param false|array $response Response, false to send the request.
	 * @param array       $args     Request arguments.
	 * @param string      $url      Request URL.
	 *
	 * @return false|array
	 */
	public function serve_fixture( $response, array $args, string $url ) {
		unset( $args );

		$fixtures = array(
			self::FEED_URL        => 'feed.xml',
			self::EDITED_FEED_URL => 'feed-edited.xml',
		);

		if ( ! isset( $fixtures[ $url ] ) ) {
			return $response;
		}

		return array(
			'headers'  => array( 'content-type' => 'text/xml; charset=utf-8' ),
			'body'     => file_get_contents( __DIR__ . '/fixtures/' . $fixtures[ $url ] ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			'response' => array(
				'code'    => 200,
				'message' => 'OK',
			),
			'cookies'  => array(),
			'filename' => null,
		);
	}

	/**
	 * Parse each feed again, as a cron run a few hours later would.
	 *
	 * @param SimplePie $feed Feed.
	 *
	 * @return void
	 */
	public function disable_feed_cache( $feed ): void {
		$feed->enable_cache( false );
	}

	/**
	 * Import a feed.
	 *
	 * @param string $url Feed URL.
	 *
	 * @return void
	 */
	private function import( string $url ): void {
		( new Weblex_Importer_Import( 'webleximporter', '0.0.0' ) )->fetch( $url );
	}

	/**
	 * Imported posts, whatever their status.
	 *
	 * @return WP_Post[]
	 */
	private function get_posts(): array {
		return get_posts(
			array(
				'post_type'   => 'weblex-importer-post',
				'post_status' => 'any',
				'numberposts' => -1,
			)
		);
	}

	/**
	 * Imported post by its slug.
	 *
	 * @param string $slug Post slug.
	 *
	 * @return WP_Post
	 */
	private function get_post( string $slug ): WP_Post {
		$posts = get_posts(
			array(
				'post_type' => 'weblex-importer-post',
				'name'      => $slug,
			)
		);

		$this->assertCount( 1, $posts );

		return $posts[0];
	}
}
