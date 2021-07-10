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

		// agenda -> L'agenda
		// phdj -> La petite histoire du jour
		// actus -> Les actus
		// quizz-hebdo -> Le quiz hebdo
		// indicateurs -> Les indicateurs
		// fiches -> Les fiches

		$feeds = array(
			array(
				'title'     => 'L’agenda',
				'url'       => 'https://www.weblex.fr/passerelle/363-1460/b3e932dfcd/flux.rss',
				'component' => 'agenda',
			),
			array(
				'title'     => 'La petite histoire du jour',
				'url'       => 'https://www.weblex.fr/passerelle/360-4b11/0eb54cc01e/flux.rss',
				'component' => 'phdj',
			),
			array(
				'title'     => 'Les actus',
				'url'       => 'https://www.weblex.fr/passerelle/361-0e00/6c4b7da188/flux.rss',
				'component' => 'actus',
			),
			array(
				'title'     => 'Le quiz hebdo',
				'url'       => 'https://www.weblex.fr/passerelle/362-78e2/ad35f6dbf4/flux.rss',
				'component' => 'quizz-hebdo',
			),
			array(
				'title'     => 'Les indicateurs',
				'url'       => 'https://www.weblex.fr/passerelle/364-162b/bfe450b810/flux.rss',
				'component' => 'indicateurs',
			),
			array(
				'title'     => 'Les fiches',
				'url'       => 'https://www.weblex.fr/passerelle/365-0cee/ae02b875da/flux.rss',
				'component' => 'fiches',
			),
		);

		$args = shortcode_atts(
			array(
				'title' => __( 'Title', 'weblexrssfeed' ),
			),
			$atts
		);

		$index = array_search( $args['title'], array_column( $feeds, 'title' ), true );
		$feed  = $feeds[ $index ];

		$html  = '<div id="app">';
		$html .= '<component is="' . $feed['component'] . '" ';
		$html .= 'data-rss = "' . $this->parse( $feed['url'] ) . '" ';
		$html .= 'data-title="' . $feed['title'] . '" ';
		$html .= 'data-url = "' . $feed['url'] . '" ';
		$html .= 'style-needed:"true" />';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Parse
	 */
	public function parse( string $url ) {
		$simplexml = simplexml_load_file( $url, 'SimpleXMLElement', LIBXML_NOCDATA );
		$array     = $this->xml2array( $simplexml );

		$array['channel']['item'] = array_slice( $simplexml->xpath( ' / rss / channel / item' ), 0, 10 );

		$json = htmlspecialchars( json_encode( $array ), ENT_QUOTES, 'UTF-8' );

		return $json;
	}

	/**
	 * xml2array
	 */
	public function xml2array( $xml_object, $out = array() ) {
		foreach ( (array) $xml_object as $index => $node ) {
			$out[ $index ] = is_object( $node ) ? $this->xml2array( $node ) : $node;
		}

		return $out;
	}
}
