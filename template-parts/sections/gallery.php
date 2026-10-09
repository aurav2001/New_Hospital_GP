<?php
/**
 * Home – Grand Opening & Hospital Gallery.
 *
 * Showcases the authentic inauguration ceremony and hospital facilities of Netrana Eye Hospital, Gaya.
 *
 * @package GPHealthcare
 */

$gallery_items = array(
	array(
		'title'    => __( 'Grand Opening & Ribbon Cutting', 'bshealthcare' ),
		'desc'     => __( 'Official inauguration at the hospital entrance by Director Dr. Arvind Kumar and team, welcoming modern eye care to Gaya.', 'bshealthcare' ),
		'tag'      => __( 'Opening Ceremony', 'bshealthcare' ),
		'img'      => BS_URI . '/assets/img/inauguration-ribbon.jpg',
		'aspect'   => 'aspect-[16/11]',
	),
	array(
		'title'    => __( 'Our Expert Medical & Nursing Team', 'bshealthcare' ),
		'desc'     => __( 'Experienced ophthalmologists, cataract surgeons, optometrists, and caring nursing staff assembled at Netrana Eye Hospital.', 'bshealthcare' ),
		'tag'      => __( 'Medical Team', 'bshealthcare' ),
		'img'      => BS_URI . '/assets/img/inauguration-team.jpg',
		'aspect'   => 'aspect-[16/11]',
	),
	array(
		'title'    => __( 'Auspicious Lamp Lighting Ceremony', 'bshealthcare' ),
		'desc'     => __( 'Traditional ceremonial diya lighting marking the blessed commencement of our mission to restore and protect vision.', 'bshealthcare' ),
		'tag'      => __( 'Ceremony', 'bshealthcare' ),
		'img'      => BS_URI . '/assets/img/inauguration-diya.jpg',
		'aspect'   => 'aspect-[16/11]',
	),
	array(
		'title'    => __( 'Hospital Dedication & Celebration', 'bshealthcare' ),
		'desc'     => __( 'Celebrating the dedication of our advanced surgical and diagnostic facilities to Gaya and surrounding communities.', 'bshealthcare' ),
		'tag'      => __( 'Celebration', 'bshealthcare' ),
		'img'      => BS_URI . '/assets/img/inauguration-celebration.jpg',
		'aspect'   => 'aspect-[16/11]',
	),
);
?>
<section id="hospital-gallery" class="section bg-gradient-to-b from-white via-primary-50/20 to-white">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Grand Opening · Gaya, Bihar', 'bshealthcare' ),
				'title'    => bs_opt( 'gallery_headline', __( 'Glimpses of Netrana Eye Hospital', 'bshealthcare' ) ),
				'subtitle' => bs_opt( 'gallery_subtitle', __( 'Bringing stitchless Phaco cataract surgery, modern diagnostics, and compassionate eye care closer to the people of Gaya.', 'bshealthcare' ) ),
				'align'    => 'center',
			)
		);
		?>

		<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
			<?php foreach ( $gallery_items as $item ) : ?>
				<div class="card-hover overflow-hidden flex flex-col group bg-white border border-navy-100/80 rounded-2xl shadow-card transition-all duration-300 hover:shadow-lift">
					<div class="relative <?php echo esc_attr( $item['aspect'] ); ?> overflow-hidden bg-navy-100">
						<img src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
						<div class="absolute inset-0 bg-gradient-to-t from-navy-950/70 via-transparent to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>
						<span class="absolute top-3 left-3 rounded-full bg-white/95 backdrop-blur px-3 py-1 text-[11px] font-bold text-primary-700 shadow-sm">
							<?php echo esc_html( $item['tag'] ); ?>
						</span>
					</div>
					<div class="p-5 flex-1 flex flex-col">
						<h3 class="font-bold text-navy-800 text-lg leading-snug mb-2 group-hover:text-primary-700 transition-colors">
							<?php echo esc_html( $item['title'] ); ?>
						</h3>
						<p class="text-sm text-navy-500 leading-relaxed">
							<?php echo esc_html( $item['desc'] ); ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Highlights strip below gallery -->
		<div class="mt-10 card p-6 md:p-8 bg-gradient-to-r from-navy-900 via-navy-850 to-primary-950 text-white rounded-2xl shadow-xl">
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 items-center divide-y sm:divide-y-0 sm:divide-x divide-white/10">
				<div class="flex items-center gap-3.5 sm:px-3 first:pl-0 pt-4 sm:pt-0">
					<span class="w-12 h-12 rounded-xl bg-primary-500/20 text-primary-300 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'shield', 22 ); ?>
					</span>
					<div>
						<h4 class="font-bold text-white text-sm"><?php esc_html_e( 'Modular Operation OT', 'bshealthcare' ); ?></h4>
						<p class="text-xs text-navy-300"><?php esc_html_e( 'Ultra-sterile surgical environment', 'bshealthcare' ); ?></p>
					</div>
				</div>

				<div class="flex items-center gap-3.5 sm:px-3 pt-4 sm:pt-0">
					<span class="w-12 h-12 rounded-xl bg-primary-500/20 text-primary-300 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'eye', 22 ); ?>
					</span>
					<div>
						<h4 class="font-bold text-white text-sm"><?php esc_html_e( 'Stitchless Phaco Surgery', 'bshealthcare' ); ?></h4>
						<p class="text-xs text-navy-300"><?php esc_html_e( 'Fast recovery, no injection/stitch', 'bshealthcare' ); ?></p>
					</div>
				</div>

				<div class="flex items-center gap-3.5 sm:px-3 pt-4 sm:pt-0">
					<span class="w-12 h-12 rounded-xl bg-primary-500/20 text-primary-300 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'user', 22 ); ?>
					</span>
					<div>
						<h4 class="font-bold text-white text-sm"><?php esc_html_e( 'Experienced Specialists', 'bshealthcare' ); ?></h4>
						<p class="text-xs text-navy-300"><?php esc_html_e( '17+ years clinical heritage', 'bshealthcare' ); ?></p>
					</div>
				</div>

				<div class="flex items-center gap-3.5 sm:px-3 last:pr-0 pt-4 sm:pt-0">
					<span class="w-12 h-12 rounded-xl bg-primary-500/20 text-primary-300 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'clock', 22 ); ?>
					</span>
					<div>
						<h4 class="font-bold text-white text-sm"><?php esc_html_e( 'Same-Day Discharge', 'bshealthcare' ); ?></h4>
						<p class="text-xs text-navy-300"><?php esc_html_e( 'Back home the same afternoon', 'bshealthcare' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
