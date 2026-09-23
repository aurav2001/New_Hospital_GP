<?php
/**
 * Home – doctors.
 *
 * @package GPHealthcare
 */

$doctors = get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => 4, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
if ( ! $doctors ) {
	return;
}
?>
<section id="doctors" class="section bg-navy-50/60">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Our Doctors', 'bshealthcare' ),
				'title'    => bs_opt( 'doctors_headline' ),
				'subtitle' => bs_opt( 'doctors_subtitle' ),
				'action'   => '<a href="' . esc_url( get_post_type_archive_link( 'bs_doctor' ) ) . '" class="btn-outline">' . esc_html__( 'View all doctors', 'bshealthcare' ) . '</a>',
			)
		);
		?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $doctors as $d ) : ?>
				<?php get_template_part( 'template-parts/doctor-card', null, array( 'id' => $d->ID ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
