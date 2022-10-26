<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/19h47/weblex-importer
 * @since      1.0.0
 *
 * @package    Weblex_Importer
 * @subpackage Weblex_Importer/admin/partials
 */

?>

<input 
	class="regular-text"
	type="url" 
	id="<?php echo esc_attr( $args['id'] ); ?>" 
	name="weblex_importer_options[<?php echo esc_attr( $args['id'] ); ?>][url]"  
	value="<?php echo isset( $options[ $args['id'] ] ) ? esc_attr( $options[ $args['id'] ]['url'] ) : ''; ?>" 
	placeholder="https://www.weblex.fr/flux.rss"
/>

<input 
	type="hidden" 
	name="weblex_importer_options[<?php echo esc_attr( $args['id'] ); ?>][date]" 
	value="<?php echo esc_attr( gmdate( 'Y-m-d H:i:s' ) ); ?>"
>

<?php if ( $term && 'weblex-importer-post' === $post_type ) { ?>
	<p class="description">
		<a href="<?php echo esc_url( get_term_link( $term->term_id, 'weblex-importer-tag' ) ); ?>" target="_blank">
			<?php echo esc_html( $args['description'] ); ?>
		</a>
	</p>
<?php } ?>

<?php if ( $term && 'post' === $post_type ) { ?>
	<p class="description">
		<a href="<?php echo esc_url( get_tag_link( $term->term_id ) ); ?>" target="_blank">
			<?php echo esc_html( $args['description'] ); ?>
		</a>
	</p>
<?php } ?>
