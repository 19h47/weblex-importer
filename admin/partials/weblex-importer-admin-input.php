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
	id="<?php echo $args['id']; ?>" 
	name="weblex_importer_options[<?php echo $args['id']; ?>][url]"  
	value="<?php echo isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ]['url'] : ''; ?>" 
	placeholder="https://www.weblex.fr/flux.rss"
/>
<input type="hidden" name="weblex_importer_options[<?php echo $args['id']; ?>][date]" value="<?php echo gmdate( 'Y-m-d H:i:s' ); ?>">

<?php if ( $term ) { ?>
	<p class="description">
		<a href="<?php echo get_term_link( $term->term_id, 'weblex-importer-tag' ); ?>" target="_blank">
			<?php echo $args['description']; ?>
		</a>
	</p>
<?php } ?>
