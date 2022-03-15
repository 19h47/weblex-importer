<?php
/**
 * The template for displaying tags
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WeblexImporter
 * @subpackage WeblexImporter/templates
 * @since 0.0.0
 */

get_header();

$description = get_queried_object() ? get_queried_object()->description : false;

if ( have_posts() ) : ?>

<div class="container-wrap">
	<div class="container">

		<header class="page-header alignwide">
			<h1 class="page-title"><?php echo get_queried_object()->name; ?></h1>
			<?php if ( $description ) : ?>
				<div class="archive-description">
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				</div>
			<?php endif; ?>
		</header><!-- .page-header -->

		<?php while ( have_posts() ) : ?>

			<?php the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<figure class="post-thumbnail">
					<a class="post-thumbnail-inner alignwide" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					</a>
					<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
						<figcaption class="wp-caption-text"><?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
					<?php endif; ?>
				</figure>

				<header class="entry-header default-max-width">

					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

					<?php

					$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

					$time_string = sprintf(
						$time_string,
						esc_attr( get_the_date( DATE_W3C ) ),
						esc_html( get_the_date() )
					);
					echo '<div class="posted-on">';
					printf(
					/* translators: %s: publish date. */
						esc_html__( 'Published %s', 'webleximporter' ),
						$time_string // phpcs:ignore WordPress.Security.EscapeOutput
					);
					echo '</div>';

					?>

					<?php echo get_the_term_list( get_the_ID(), 'weblex-importer-category', '<div>', ', ', '</div>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_excerpt(); ?>
					<?php if ( get_the_content() ) : ?>
						<p>
							<a href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'webleximporter' ); ?></a>
						</p>
					<?php endif; ?>

					<?php
					wp_link_pages(
						array(
							'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'webleximporter' ) . '">',
							'after'    => '</nav>',
							/* translators: %: page number. */
							'pagelink' => esc_html__( 'Page %', 'webleximporter' ),
						)
					);
					?>
				</div><!-- .entry-content -->
			</article><!-- #post-${ID} -->
		<?php endwhile; ?>

		<?php the_posts_pagination(); ?>

		<?php else : ?>
			<section class="no-results not-found">
				<header class="page-header alignwide">
					<h1 class="page-title">
						<?php esc_html_e( 'Nothing here', 'webleximporter' ); ?>
					</h1>
				</header><!-- .page-header -->

				<div class="page-content default-max-width">
					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'webleximporter' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section><!-- .no-results -->
		<?php endif; ?>

	</div>
</div>  

<?php
get_footer();
