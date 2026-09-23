<?php
/**
 * Home – specialities grid.
 *
 * @package GPHealthcare
 */

$services = get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 8, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
if ( ! $services ) {
	return;
}
?>
<section id="services" class="section">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Our Specialities', 'bshealthcare' ),
				'title'    => bs_opt( 'services_headline' ),
				'subtitle' => bs_opt( 'services_subtitle' ),
			)
		);
		?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $services as $s ) : ?>
				<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="card-hover overflow-hidden group flex flex-col">
					<div class="relative aspect-[16/10] overflow-hidden bg-navy-50">
						<?php if ( has_post_thumbnail( $s ) ) : ?>
							<?php echo get_the_post_thumbnail( $s, 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'eye', 48 ); ?></span>
						<?php endif; ?>
					</div>
					<div class="p-5 flex-1 flex flex-col">
						<h3 class="font-bold text-navy-800 text-lg mb-1.5 group-hover:text-primary-700"><?php echo esc_html( get_the_title( $s ) ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed line-clamp-3"><?php echo esc_html( get_the_excerpt( $s ) ); ?></p>
						<span class="mt-4 text-sm font-semibold text-primary-700 inline-flex items-center gap-1"><?php esc_html_e( 'Learn more', 'bshealthcare' ); ?> <?php bs_the_icon( 'chevron-right', 14 ); ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="mt-8 text-center">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_service' ) ); ?>" class="btn-outline"><?php esc_html_e( 'View all specialities', 'bshealthcare' ); ?> <?php bs_the_icon( 'chevron-right', 16 ); ?></a>
		</div>
	</div>
</section>
