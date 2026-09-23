<?php
/**
 * Home – Why Choose Us.
 *
 * @package BSHealthcare
 */

$items = bs_parse_lines( bs_opt( 'why_items' ), array( 'title', 'desc', 'img' ) );
if ( ! $items ) {
	return;
}
$icons = array( 'award', 'calendar', 'heart', 'shield' );
?>
<section class="section bg-navy-50/60">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Why Choose Us', 'bshealthcare' ),
				'title'    => bs_opt( 'why_headline' ),
				'subtitle' => bs_opt( 'why_subtitle' ),
				'align'    => 'center',
			)
		);
		?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $items as $i => $it ) : ?>
				<div class="card-hover overflow-hidden group">
					<div class="relative h-44 overflow-hidden bg-navy-100">
						<?php if ( $it['img'] ) : ?>
							<img src="<?php echo esc_url( $it['img'] ); ?>" alt="<?php echo esc_attr( $it['title'] ); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
						<?php endif; ?>
						<div class="absolute inset-0 bg-gradient-to-t from-navy-900/60 to-transparent"></div>
						<span class="absolute left-4 bottom-4 w-11 h-11 rounded-xl bg-white text-primary-700 flex items-center justify-center shadow-soft"><?php bs_the_icon( $icons[ $i % 4 ], 20 ); ?></span>
					</div>
					<div class="p-5">
						<h3 class="font-bold text-navy-800 text-lg mb-1.5"><?php echo esc_html( $it['title'] ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( $it['desc'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mt-10 card p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-5 border-primary-100 bg-gradient-to-r from-white to-primary-50/60">
			<div>
				<h3 class="text-xl font-bold text-navy-800 mb-1"><?php esc_html_e( 'Not sure which treatment you need?', 'bshealthcare' ); ?></h3>
				<p class="text-navy-500 text-sm"><?php esc_html_e( 'Talk to our care team and we will guide you to the right specialist.', 'bshealthcare' ); ?></p>
			</div>
			<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="btn-primary shrink-0"><?php esc_html_e( 'Contact Us', 'bshealthcare' ); ?> <?php bs_the_icon( 'arrow-right', 16 ); ?></a>
		</div>
	</div>
</section>
