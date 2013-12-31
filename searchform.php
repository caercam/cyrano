<?php
/**
 * The template for Search Form.
 *
 * @package WordPress
 * @subpackage Cyrano
 * @since Cyrano 1.0
 */
?>

				<div class="searchform">
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
						<label>
							<span class="screen-reader-text"><?php _e( 'Search for:', 'label' ); ?></span>
							<input type="search" class="search-field" placeholder="<?php _e( 'Search &hellip;', 'placeholder' ); ?>" value="<?php get_search_query(); ?>" name="s" title="<?php _e( 'Search for:', 'label' ); ?>" />
						</label>
						<input type="submit" class="search-submit" value="<?php _e( 'Search', 'submit button' ); ?>" />
					</form>
				</div>