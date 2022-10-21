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

<fieldset>
	<legend class="screen-reader-text">
		<span><?php echo esc_html( $args['description'] ); ?></span>
	</legend>
	
	<label for="<?php echo esc_attr( $args['id'] ); ?>">
		<input 
			name="weblex_importer_options[<?php echo esc_attr( $args['id'] ); ?>]" 
			type="checkbox" 
			id="<?php echo esc_attr( $args['id'] ); ?>" 
			value="<?php echo isset( $options['post'] ) ? esc_attr( $options['post'] ) : '1'; ?>" 
			<?php checked( 1, $options['post'] ); ?>
		>
		<?php echo esc_html( $args['description'] ); ?>
	</label>
</fieldset>
