<?php
/**
 * Doctors archive.
 *
 * @package GPHealthcare
 */

get_header();
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Our Team', 'bshealthcare' ),
			'title'    => __( 'Meet our doctors', 'bshealthcare' ),
			'subtitle' => bs_opt( 'doctors_subtitle' ),
			'crumbs'   => array( array( __( 'Doctors', 'bshealthcare' ) ) ),
		)
	);
	?>
	<section class="section">
		<div class="container-x">
			<div class="mb-8 max-w-md">
				<label class="relative block">
					<span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-navy-400"><?php bs_the_icon( 'user', 16 ); ?></span>
					<input type="search" id="bs-doctor-search" class="input pl-10" placeholder="<?php esc_attr_e( 'Search doctor or speciality…', 'bshealthcare' ); ?>">
				</label>
			</div>
			<?php if ( have_posts() ) : ?>
				<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5" id="bs-doctor-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="bs-doctor-item" data-search="<?php echo esc_attr( strtolower( get_the_title() . ' ' . get_post_meta( get_the_ID(), '_bs_role', true ) . ' ' . get_post_meta( get_the_ID(), '_bs_keyword', true ) ) ); ?>">
							<?php get_template_part( 'template-parts/doctor-card', null, array( 'id' => get_the_ID() ) ); ?>
						</div>
					<?php endwhile; ?>
				</div>
				<p id="bs-doctor-empty" class="hidden card p-12 text-center text-navy-500"><?php esc_html_e( 'No doctors match your search.', 'bshealthcare' ); ?></p>
			<?php else : ?>
				<div class="card p-12 text-center">
					<span class="w-16 h-16 mx-auto rounded-2xl bg-navy-50 text-navy-300 flex items-center justify-center mb-4"><?php bs_the_icon( 'user', 28 ); ?></span>
					<h3 class="text-xl font-bold text-navy-800 mb-1"><?php esc_html_e( 'No doctors available', 'bshealthcare' ); ?></h3>
					<p class="text-navy-500 text-sm"><?php esc_html_e( 'Please check back later.', 'bshealthcare' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
