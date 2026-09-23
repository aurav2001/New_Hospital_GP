<?php
/**
 * Search form.
 *
 * @package BSHealthcare
 */
?>
<form role="search" method="get" class="flex gap-2 max-w-md mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="bs-s"><?php esc_html_e( 'Search', 'bshealthcare' ); ?></label>
	<input type="search" id="bs-s" class="input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search articles…', 'bshealthcare' ); ?>">
	<button type="submit" class="btn-primary shrink-0"><?php esc_html_e( 'Search', 'bshealthcare' ); ?></button>
</form>
