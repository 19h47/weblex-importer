<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://devinvinson.com
 * @since      1.0.0
 *
 * @package    WebLex_RSS_Feed
 * @subpackage WebLex_RSS_Feed/admin/partials
 */
?>

<input 
	class="regular-text"
	type="url" 
	id="<?php echo $args['id']; ?>" 
	name="weblex_rss_feed_options[<?php echo $args['id']; ?>][url]"  
	value="<?php echo isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ]['url'] : ''; ?>" 
	placeholder="https://www.weblex.fr/flux.rss"
/>
<input type="hidden" name="weblex_rss_feed_options[<?php echo $args['id']; ?>][date]" value="<?php echo gmdate( 'Y-m-d H:i:s' ); ?>">
<p class="description"><?php echo $args['description']; ?></p>
