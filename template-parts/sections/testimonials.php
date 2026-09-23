<?php
/**
 * Home – testimonials slider.
 *
 * @package GPHealthcare
 */

$items = get_posts( array( 'post_type' => 'bs_testimonial', 'numberposts' => 6, 'orderby' => 'menu_order date', 'order' => 'ASC' ) );
if ( ! $items ) {
	return;
}
?>
<section class="section">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow' => __( 'Patient Stories', 'bshealthcare' ),
				'title'   => bs_opt( 'testimonials_headline' ),
				'align'   => 'center',
			)
		);
		?>
		<div class="card overflow-hidden" id="bs-testimonials">
			<div class="grid lg:grid-cols-2">
				<div class="relative aspect-[4/3] lg:aspect-auto lg:min-h-[420px] bg-navy-100">
					<?php foreach ( $items as $i => $t ) : ?>
						<div class="bs-t-media absolute inset-0 transition-opacity duration-500 <?php echo 0 === $i ? '' : 'opacity-0 pointer-events-none'; ?>" data-index="<?php echo esc_attr( $i ); ?>">
							<?php if ( has_post_thumbnail( $t ) ) : ?>
								<?php echo get_the_post_thumbnail( $t, 'large', array( 'class' => 'w-full h-full object-cover', 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'quote', 72 ); ?></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="p-7 md:p-10 lg:p-12 flex flex-col justify-center relative">
					<span class="absolute top-6 right-8 text-primary-100"><?php bs_the_icon( 'quote', 64 ); ?></span>
					<?php foreach ( $items as $i => $t ) : ?>
						<div class="bs-t-text <?php echo 0 === $i ? '' : 'hidden'; ?>" data-index="<?php echo esc_attr( $i ); ?>">
							<div class="flex gap-0.5 text-amber-400 mb-5">
								<?php for ( $s = 0; $s < 5; $s++ ) { bs_the_icon( 'star', 16, 'fill-current' ); } ?>
							</div>
							<p class="text-lg md:text-xl text-navy-700 leading-relaxed mb-6">&ldquo;<?php echo esc_html( wp_strip_all_tags( $t->post_content ) ); ?>&rdquo;</p>
							<div>
								<p class="font-bold text-navy-800"><?php echo esc_html( $t->post_title ); ?></p>
								<p class="text-xs font-semibold uppercase tracking-wide text-primary-700"><?php echo esc_html( get_post_meta( $t->ID, '_bs_role', true ) ? get_post_meta( $t->ID, '_bs_role', true ) : __( 'Patient', 'bshealthcare' ) ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>

					<div class="flex items-center gap-3 mt-8 pt-6 border-t border-navy-100">
						<button type="button" class="w-10 h-10 rounded-xl border border-navy-200 flex items-center justify-center hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-colors" data-t-prev aria-label="<?php esc_attr_e( 'Previous', 'bshealthcare' ); ?>"><?php bs_the_icon( 'chevron-left', 18 ); ?></button>
						<button type="button" class="w-10 h-10 rounded-xl border border-navy-200 flex items-center justify-center hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-colors" data-t-next aria-label="<?php esc_attr_e( 'Next', 'bshealthcare' ); ?>"><?php bs_the_icon( 'chevron-right', 18 ); ?></button>
						<div class="flex gap-1.5 ml-2">
							<?php foreach ( $items as $i => $t ) : ?>
								<button type="button" class="bs-t-dot h-1.5 rounded-full transition-all <?php echo 0 === $i ? 'w-6 bg-primary-600' : 'w-1.5 bg-navy-200'; ?>" data-index="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: story number */ __( 'Story %d', 'bshealthcare' ), $i + 1 ) ); ?>"></button>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
