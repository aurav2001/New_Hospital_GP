<?php
/**
 * Template Name: Blog Page
 * Template for Blog page (slug: blog).
 *
 * @package GPHealthcare
 */

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

query_posts(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'paged'          => $paged,
	)
);

require locate_template( 'index.php' );

wp_reset_query();
