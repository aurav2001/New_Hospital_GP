<?php
/**
 * Home – Ayushman Bharat scheme.
 *
 * @package GPHealthcare
 */

$benefits = bs_parse_lines( bs_opt( 'ayushman_benefits' ), array( 'title', 'desc', 'img' ) );
if ( ! $benefits ) {
	return;
}
?>
<section class="section bg-gradient-to-b from-white to-amber-50/40">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Government Approved Scheme', 'bshealthcare' ),
				'title'    => bs_opt( 'ayushman_headline' ),
				'subtitle' => bs_opt( 'ayushman_subtitle' ),
				'align'    => 'center',
			)
		);
		?>
		<div class="grid md:grid-cols-3 gap-5">
			<?php foreach ( $benefits as $b ) : ?>
				<div class="card-hover overflow-hidden flex flex-col group">
					<div class="relative aspect-[16/10] bg-navy-50 overflow-hidden">
						<?php if ( $b['img'] ) : ?>
							<img src="<?php echo esc_url( $b['img'] ); ?>" alt="<?php echo esc_attr( $b['title'] ); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-primary-300"><?php bs_the_icon( 'credit', 48 ); ?></span>
						<?php endif; ?>
					</div>
					<div class="p-5 flex-1">
						<h3 class="font-bold text-navy-800 text-lg mb-1.5"><?php echo esc_html( $b['title'] ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( $b['desc'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mt-8 card p-5 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 border-amber-200 bg-amber-50/70">
			<p class="flex items-center gap-3 text-sm text-navy-700"><span class="text-emerald-600 shrink-0"><?php bs_the_icon( 'check-circle', 20 ); ?></span> <?php echo esc_html( bs_opt( 'ayushman_note' ) ); ?></p>
			<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-dark shrink-0"><?php bs_the_icon( 'phone', 16 ); ?> <?php esc_html_e( 'Check eligibility', 'bshealthcare' ); ?></a>
		</div>
	</div>
</section>
