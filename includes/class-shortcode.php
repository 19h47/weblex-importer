<?php // phpcs:ignore
/**
 * ShortcodeButton
 *
 * @package WebLexRSSFeed
 */

/**
 * Shortcode
 */
class Shortcode {

	/**
	 * Runs initialization tasks.
	 *
	 * @see https://core.trac.wordpress.org/browser/tags/5.6/src/wp-includes/shortcodes.php#L63
	 *
	 * @return void
	 */
	public function run() : void {
		add_shortcode( 'feed', array( $this, 'feed' ) );
	}

	/**
	 * Button
	 *
	 * @param array       $atts Array of attributes.
	 * @param string|null $content The shortcode content or null if not set.
	 * @param string      $shortcode_tag The shortcode tag.
	 *
	 * @return string
	 */
	public function feed( array $atts, string $content, string $shortcode_tag ) : string {
		$feeds = array(
			'La petite histoire du jour' => 'https://www.weblex.fr/passerelle/360-4b11/0eb54cc01e/flux.rss',
			'Les actus'                  => 'https://www.weblex.fr/passerelle/361-0e00/6c4b7da188/flux.rss',
			'Le quiz hebdo'              => 'https://www.weblex.fr/passerelle/362-78e2/ad35f6dbf4/flux.rss',
			'L’agenda'                   => 'https://www.weblex.fr/passerelle/363-1460/b3e932dfcd/flux.rss',
			'Les indicateurs'            => 'https://www.weblex.fr/passerelle/364-162b/bfe450b810/flux.rss',
			'Les fiches'                 => 'https://www.weblex.fr/passerelle/365-0cee/ae02b875da/flux.rss',
		);

		$args = shortcode_atts(
			array(
				'name' => __( 'Name', 'weblexrssfeed' ),
			),
			$atts
		);

		return '<div id="app" data-rss="' . $this->parse( $feeds[ $args['name'] ] ) . '" data-name="' . $args['name'] . '" data-url="' . $feeds[ $args['name'] ] . '"></div>';
	}


	public function parse( string $url ) {
		$simplexml = simplexml_load_file( $url, 'SimpleXMLElement', LIBXML_NOCDATA );
		$json      = htmlspecialchars( json_encode( $simplexml ), ENT_QUOTES, 'UTF-8' );

		return $json;
	}
}
