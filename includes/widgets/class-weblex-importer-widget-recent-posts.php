<?php
/**
 * Widget Recent Posts
 *
 * @link       https://github.com/19h47/weblex-importer/
 * @since      0.5.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin/widgets
 */

/**
 * Recent Posts
 */
class Weblex_Importer_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_recent_entries',
			'description'                 => __( 'Your site&#8217;s most recent Weblex Posts.', 'webleximporter' ),
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		);

		parent::__construct( 'weblex-importer-recent-posts', __( 'Weblex Importer Recent Posts', 'webleximporter' ), $widget_ops );

		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$default_title = __( 'Recent Posts', 'webleximporter' );
		$title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date      = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;

		$r = new WP_Query(
			/**
			 * Filters the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @see WP_Query::get_posts()
			 *
			 * @param array $args     An array of arguments used to retrieve the recent posts.
			 * @param array $instance Array of settings for the current widget.
			 */
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page'      => $number,
					'post_type'           => 'weblex-importer-post',
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
				),
				$instance
			)
		);

		if ( ! $r->have_posts() ) {
			return;
		}
		?>

		<?php echo wp_kses_post( $args['before_widget'] ); ?>

		<?php
		if ( $title ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		$format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

		/** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
		$format = apply_filters( 'navigation_widgets_format', $format );

		if ( 'html5' === $format ) {
			// The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
			$title      = trim( wp_strip_all_tags( $title ) );
			$aria_label = $title ? $title : $default_title;
			echo '<nav aria-label="' . esc_attr( $aria_label ) . '">';
		}
		?>

		<<?php echo esc_attr( $show_thumbnail ? 'div' : 'ul' ); ?> <?php echo $show_thumbnail ? 'style="display: flex; flex-wrap: wrap; row-gap: 30px; margi-right: -1rem; margin-left: -1rem;"' : ''; ?>>
			<?php foreach ( $r->posts as $recent_post ) : ?>
				<?php
				$post_title   = get_the_title( $recent_post->ID );
				$title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)', 'webleximporter' );
				$aria_current = '';

				if ( get_queried_object_id() === $recent_post->ID ) {
					$aria_current = ' aria-current="page"';
				}
				?>

				<?php if ( $show_thumbnail ) : ?>
					<div style="padding-right: 1rem; padding-left: 1rem; width: <?php echo esc_attr( round( 100 / $number, 8 ) ); ?>%;">
						<article 
							id="<?php echo esc_attr( $recent_post->post_type ); ?>-<?php echo esc_attr( $recent_post->ID ); ?>"
							class="<?php echo esc_attr( implode( ' ', get_post_class( '', $recent_post->ID ) ) ); ?>"
							style=""
						>
							<?php if ( has_post_thumbnail( $recent_post->ID ) ) : ?>
								<figure class="post-thumbnail">
									<a href="<?php echo esc_url( get_the_permalink( $recent_post->ID ) ); ?>">
										<?php echo wp_kses_post( get_the_post_thumbnail( $recent_post->ID ) ); ?>
									</a>
								</figure>
							<?php endif ?>

							<header 
								class="entry-header" 
								style="<?php echo has_post_thumbnail( $recent_post->ID ) ? 'margin-top: 1.5rem;"' : ''; ?>"
							>
								<h2 class="entry-title" style="font-size: 1.5rem;">
									<a href="<?php echo esc_url( get_the_permalink( $recent_post->ID ) ); ?>">
										<?php echo esc_html( $title ); ?>
									</a>
								</h2>
							</header>

							<div class="entry-content" style="font-size: 0.875rem; margin-top: 1.5rem;">
								<?php echo wp_kses_post( get_the_excerpt( $recent_post->ID ) ); ?>
							</div>

							<?php if ( has_term( '', 'weblex-importer-tag', $recent_post->ID ) || has_term( '', 'weblex-importer-category', $recent_post->ID ) ) : ?>
								<div class="entry-footer" style="font-size: 0.875rem; margin-top: 0.5rem;">
									<?php if ( has_term( '', 'weblex-importer-tag', $recent_post->ID ) ) : ?>
										<?php
										printf(
											/* translators: %s: list of tags. */
											'<p style="margin: 0;" class="tags-links">' . esc_html__( 'Tagged as %s', 'webleximporter' ) . ' </p>',
											get_the_term_list( $recent_post->ID, 'weblex-importer-tag', '', ', ' )
										);
										?>
									<?php endif ?>

									<?php if ( has_term( '', 'weblex-importer-category', $recent_post->ID ) ) : ?>
										<?php $categories = get_the_terms( $recent_post->ID, 'weblex-importer-category' ); ?>
										<?php
										printf(
											/* translators: %s: list of categories. */
											'<p style="margin: 0;" class="cat-links">' . esc_html( _n( 'Categorized as %s', 'Categorized as %s', count( $categories ), 'webleximporter' ) ) . ' </p>',
											get_the_term_list( $recent_post->ID, 'weblex-importer-category', '', ', ' )
										);
										?>
									<?php endif ?>
								</div>
							<?php endif ?>

						</article>
					</div>
				<?php else : ?>
					<li>
						<a href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo esc_attr( $aria_current ); ?>>
							<?php echo esc_html( $title ); ?>
						</a>
						<?php if ( $show_date ) : ?>
							<span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</<?php echo esc_attr( $show_thumbnail ? 'div' : 'ul' ); ?>>

		<?php
		if ( 'html5' === $format ) {
			echo '</nav>';
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;
		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		$instance['number']         = (int) $new_instance['number'];
		$instance['show_date']      = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title          = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date      = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:' ); ?>
			</label>
			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title ); ?>" 
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show:', 'webleximporter' ); ?>
			</label>
			<input 
				class="tiny-text" 
				id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" 
				type="number" 
				step="1" 
				min="1" 
				value="<?php echo esc_attr( $number ); ?>" 
				size="3" 
			/>
		</p>

		<p>
			<input 
				class="checkbox" 
				type="checkbox"<?php checked( $show_date ); ?> 
				id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" 
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<?php esc_html_e( 'Display post date?', 'webleximporter' ); ?>
			</label>
		</p>

		<p>
			<input 
				class="checkbox" 
				type="checkbox"<?php checked( $show_thumbnail ); ?> 
				id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" 
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>">
				<?php esc_html_e( 'Display thumbnail?', 'webleximporter' ); ?>
			</label>
		</p>
		<?php
	}
}
