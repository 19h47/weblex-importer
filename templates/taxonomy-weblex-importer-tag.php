<?php
/**
 * The template for displaying tags
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package           WebLexImporter
 * @subpackage WebLexImporter/templates
 * @since 0.0.0
 */

get_header();

if ( have_posts() ) : ?>

	<header class="page-header alignwide">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description">
				<?php echo wp_kses_post( wpautop( $description ) ); ?>
			</div>
		<?php endif; ?>
	</header><!-- .page-header -->

	<?php while ( have_posts() ) : ?>
		
		<?php the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php if ( is_singular() ) : ?>
					<?php the_title( '<h1 class="entry-title default-max-width">', '</h1>' ); ?>
				<?php else : ?>
					<?php the_title( sprintf( '<h2 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_content(); ?>

				<?php
				wp_link_pages(
					array(
						'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'weblex-importer' ) . '">',
						'after'    => '</nav>',
						/* translators: %: page number. */
						'pagelink' => esc_html__( 'Page %', 'weblex-importer' ),
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
					<?php esc_html_e( 'Nothing here', 'weblex-importer' ); ?>
				</h1>
			</header><!-- .page-header -->

			<div class="page-content default-max-width">
				<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'weblex-importer' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .page-content -->
		</section><!-- .no-results -->
<?php endif; ?>

<?php
get_footer();
