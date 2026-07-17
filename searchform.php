<?php
/**
 * Search form.
 *
 * @package KHAS
 */
?>
<form role="search" method="get" class="khas-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="khas-s"><?php esc_html_e( 'جستجو', 'khas' ); ?></label>
	<input type="search" id="khas-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'جستجو در مجله خاص…', 'khas' ); ?>" />
	<button type="submit" class="khas-btn"><?php esc_html_e( 'جستجو', 'khas' ); ?></button>
</form>
