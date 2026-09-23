<?php
/**
 * Home – FAQ accordion.
 *
 * @package GPHealthcare
 */

$faqs = bs_parse_lines( bs_opt( 'faq_items' ), array( 'q', 'a' ) );
if ( ! $faqs ) {
	return;
}
?>
<section class="section">
	<div class="container-x grid lg:grid-cols-12 gap-10">
		<div class="lg:col-span-4">
			<?php
			bs_section_header(
				array(
					'eyebrow'  => __( 'Patient Education', 'bshealthcare' ),
					'title'    => bs_opt( 'faq_headline' ),
					'subtitle' => __( 'Straight answers to the questions patients ask us most.', 'bshealthcare' ),
				)
			);
			?>
			<div class="card p-6 bg-primary-50/60 border-primary-100">
				<span class="icon-tile mb-4 bg-white"><?php bs_the_icon( 'message', 22 ); ?></span>
				<h3 class="font-bold text-navy-800 mb-1"><?php esc_html_e( 'Still have questions?', 'bshealthcare' ); ?></h3>
				<p class="text-sm text-navy-500 mb-4"><?php esc_html_e( 'Our care team is happy to help you understand your treatment options.', 'bshealthcare' ); ?></p>
				<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="btn-primary w-full"><?php esc_html_e( 'Ask our team', 'bshealthcare' ); ?></a>
			</div>
		</div>

		<div class="lg:col-span-8 space-y-3" id="bs-faq">
			<?php foreach ( $faqs as $i => $f ) : ?>
				<div class="card bs-faq-item <?php echo 0 === $i ? 'is-open border-primary-200' : ''; ?>">
					<button type="button" class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center justify-between gap-4 font-bold text-navy-800 hover:text-primary-700" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<span><?php echo esc_html( $f['q'] ); ?></span>
						<span class="bs-faq-icon w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors <?php echo 0 === $i ? 'bg-primary-600 text-white' : 'bg-navy-50 text-navy-500'; ?>">
							<span class="bs-faq-plus <?php echo 0 === $i ? 'hidden' : ''; ?>"><?php bs_the_icon( 'plus', 16 ); ?></span>
							<span class="bs-faq-minus <?php echo 0 === $i ? '' : 'hidden'; ?>"><?php bs_the_icon( 'minus', 16 ); ?></span>
						</span>
					</button>
					<div class="bs-faq-body grid transition-all duration-300 <?php echo 0 === $i ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'; ?>">
						<div class="overflow-hidden"><p class="px-5 md:px-6 pb-5 text-navy-500 leading-relaxed"><?php echo esc_html( $f['a'] ); ?></p></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
