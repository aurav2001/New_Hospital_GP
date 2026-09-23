<?php
/**
 * Default page template.
 *
 * @package GPHealthcare
 */

get_header();
the_post();

/*
 * The front page always shows the hospital home sections, even if the page was
 * created before this theme was installed and therefore carries no page template.
 * Any content typed into the page editor is rendered above the sections.
 */
if ( is_front_page() ) :
	$bs_content = trim( wp_strip_all_tags( get_the_content() ) );
	if ( $bs_content ) :
		?>
		<section class="section-tight">
			<div class="container-x max-w-3xl prose-cms"><?php the_content(); ?></div>
		</section>
		<?php
	endif;

	foreach ( array_filter( array_map( 'trim', explode( ',', bs_opt( 'home_sections' ) ) ) ) as $bs_section ) {
		get_template_part( 'template-parts/sections/' . sanitize_file_name( $bs_section ) );
	}
else :
	?>
	<main>
		<?php
		get_template_part(
			'template-parts/page-hero',
			null,
			array(
				'title'  => get_the_title(),
				'crumbs' => array( array( get_the_title() ) ),
			)
		);
		?>
		<section class="section">
			<div class="container-x max-w-3xl prose-cms">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="mt-6 text-sm font-semibold text-primary-700">', 'after' => '</div>' ) );
				?>
			</div>
		</section>
	</main>
	<?php
endif;

get_footer();
