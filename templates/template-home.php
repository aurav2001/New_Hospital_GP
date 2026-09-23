<?php
/**
 * Template Name: Hospital Home
 *
 * Renders the sections listed in Hospital Settings → Home Page → Sections.
 *
 * @package BSHealthcare
 */

get_header();

$bs_sections = array_filter( array_map( 'trim', explode( ',', bs_opt( 'home_sections' ) ) ) );
foreach ( $bs_sections as $bs_section ) {
	get_template_part( 'template-parts/sections/' . sanitize_file_name( $bs_section ) );
}

get_footer();
